import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount } from '@vue/test-utils';
import Reservation from '@/pages/landingpage/reservation.vue';

// We need a slightly more advanced mock for useForm to trigger callbacks
let mockPost = vi.fn();
let mockReset = vi.fn();

vi.mock('@inertiajs/vue3', async (importOriginal) => {
    const actual = await importOriginal<typeof import('@inertiajs/vue3')>();
    return {
        ...actual,
        useForm: (data: any) => ({
            ...data,
            post: (url: string, options: any) => mockPost(url, options),
            reset: mockReset,
            errors: {},
            processing: false,
        }),
    };
});

describe('reservation.vue', () => {
    const mockTables = [
        { id: 1, table_number: '101', qr_code_path: '', status: 'available' },
        { id: 2, table_number: '102', qr_code_path: '', status: 'available' },
        { id: 3, table_number: '103', qr_code_path: '', status: 'occupied' }
    ];

    const defaultStubs = {
        Header: true,
        AlertDialog: { template: '<div><slot/></div>' },
        AlertDialogTrigger: { template: '<div><slot/></div>' },
        AlertDialogContent: { template: '<div><slot/></div>' },
        AlertDialogHeader: { template: '<div><slot/></div>' },
        AlertDialogTitle: { template: '<div><slot/></div>' },
        AlertDialogDescription: { template: '<div><slot/></div>' },
        AlertDialogFooter: { template: '<div><slot/></div>' },
        AlertDialogCancel: { template: '<div><slot/></div>' },
        AlertDialogAction: { template: '<div><slot/></div>' },
    };

    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('button remains disabled until required fields are filled', async () => {
        const wrapper = mount(Reservation, {
            props: { Tables: mockTables },
            global: { stubs: defaultStubs }
        });

        // Find confirm button
        const confirmButton = wrapper.find('button.w-full.bg-orange-600');
        expect(confirmButton.attributes('disabled')).toBeDefined();

        // Fill Name
        await wrapper.find('input[type="text"]').setValue('John Doe');
        expect(confirmButton.attributes('disabled')).toBeDefined();

        // Fill Date
        await wrapper.find('input[type="date"]').setValue('2026-10-10');
        expect(confirmButton.attributes('disabled')).toBeDefined();

        // Fill Time
        await wrapper.find('select').setValue('14:00');
        expect(confirmButton.attributes('disabled')).toBeDefined();

        // Select Table
        const tableButtons = wrapper.findAll('button').filter(b => b.text().includes('101'));
        await tableButtons[0].trigger('click');

        // Now button should be enabled
        expect(confirmButton.attributes('disabled')).toBeUndefined();
    });

    it('custom alert shows warning if form is incomplete', async () => {
        const wrapper = mount(Reservation, {
            props: { Tables: mockTables },
            global: { stubs: defaultStubs }
        });

        const vm = wrapper.vm as any;
        vm.StoreForm(); // Trigger submission programmatically

        await wrapper.vm.$nextTick();
        
        // Assert Custom Alert UI for warning
        expect(wrapper.text()).toContain('Data Belum Lengkap');
        expect(wrapper.text()).toContain('Mohon lengkapi semua field');
    });

    it('custom alert shows error when submission fails', async () => {
        const wrapper = mount(Reservation, {
            props: { Tables: mockTables },
            global: { stubs: defaultStubs }
        });

        // Fill complete form
        await wrapper.find('input[type="text"]').setValue('John Doe');
        await wrapper.find('input[type="date"]').setValue('2026-10-10');
        await wrapper.find('select').setValue('14:00');
        const tableButtons = wrapper.findAll('button').filter(b => b.text().includes('101'));
        await tableButtons[0].trigger('click');

        // Provide a mock implementation that triggers onError
        mockPost.mockImplementationOnce((url, options) => {
            if (options.onError) {
                options.onError({ table_id: 'Meja sudah dipesan orang lain' });
            }
        });

        const vm = wrapper.vm as any;
        vm.StoreForm();

        await wrapper.vm.$nextTick();
        
        // Assert Custom Alert UI for error
        expect(wrapper.text()).toContain('Reservasi Gagal');
        expect(wrapper.text()).toContain('Meja sudah dipesan orang lain');
    });

    it('shows success alert and generates correct WhatsApp URL on success', async () => {
        const windowOpenSpy = vi.spyOn(window, 'open').mockImplementation(() => null);
        
        const wrapper = mount(Reservation, {
            props: { Tables: mockTables },
            global: { stubs: defaultStubs }
        });

        // Fill complete form
        await wrapper.find('input[type="text"]').setValue('Alice');
        await wrapper.find('input[type="number"]').setValue(3);
        await wrapper.find('input[type="date"]').setValue('2026-12-25');
        await wrapper.find('select').setValue('19:00');
        const tableButtons = wrapper.findAll('button').filter(b => b.text().includes('102'));
        await tableButtons[0].trigger('click');

        // Provide a mock implementation that triggers onSuccess
        mockPost.mockImplementationOnce((url, options) => {
            if (options.onSuccess) {
                options.onSuccess();
            }
        });

        const vm = wrapper.vm as any;
        vm.StoreForm();

        await wrapper.vm.$nextTick();

        // Assert Custom Alert UI for success
        expect(wrapper.text()).toContain('Reservasi Berhasil!');
        
        // Simulate clicking 'Lanjut ke WhatsApp'
        const buttons = wrapper.findAll('button');
        const proceedBtn = buttons.find(b => b.text().includes('Lanjut ke WhatsApp'));
        await proceedBtn?.trigger('click');
        
        // Assert WhatsApp URL Generation
        expect(windowOpenSpy).toHaveBeenCalledTimes(1);
        const calledUrl = windowOpenSpy.mock.calls[0][0] as string;
        
        expect(calledUrl).toContain('https://wa.me/6282268822307?text=');
        
        // Decode to check the payload
        const decodedMessage = decodeURIComponent(calledUrl.split('text=')[1]);
        expect(decodedMessage).toContain('Meja: 102');
        expect(decodedMessage).toContain('Tanggal: 2026-12-25');
        expect(decodedMessage).toContain('Jam: 19:00 WIB');
        expect(decodedMessage).toContain('Nama: Alice');
        expect(decodedMessage).toContain('Jumlah Tamu: 3 Orang');
    });
});
