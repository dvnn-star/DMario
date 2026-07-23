import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import OrderStatus from '@/pages/MenuQr/OrderStatus.vue';

describe('OrderStatus.vue', () => {
    const mockOrder = {
        id: 1234,
        status: 'pending' as const,
        payment_method: 'qris',
        total_price: 35000,
        created_at: '2026-07-23T12:00:00Z',
        table: { id: 1, table_number: 5 },
        order_details: [
            {
                id: 1,
                quantity: 1,
                price: 25000,
                menu_item: { id: 1, name: 'Nasi Goreng', price: 25000 }
            },
            {
                id: 2,
                quantity: 2,
                price: 5000,
                note: 'Es sedikit',
                menu_item: { id: 2, name: 'Es Teh', price: 5000 }
            }
        ]
    };

    it('renders pending status correctly', () => {
        const wrapper = mount(OrderStatus, {
            props: {
                order: mockOrder
            },
            global: {
                stubs: {
                    Head: true,
                    Header: true
                }
            }
        });

        expect(wrapper.text()).toContain('Order #1234');
        expect(wrapper.text()).toContain('Meja 5');
        expect(wrapper.text()).toContain('Pesanan Diterima'); // pending title
        expect(wrapper.text()).toContain('Nasi Goreng');
        expect(wrapper.text()).toContain('Es Teh');
        expect(wrapper.text()).toContain('Es sedikit'); // note
        expect(wrapper.text()).toMatch(/qris/i);
        expect(wrapper.text()).toMatch(/Rp\s*35\.000/);
    });

    it('renders completed status correctly', () => {
        const completedOrder = { ...mockOrder, status: 'completed' as const };
        const wrapper = mount(OrderStatus, {
            props: {
                order: completedOrder
            },
            global: {
                stubs: {
                    Head: true,
                    Header: true
                }
            }
        });

        expect(wrapper.text()).toContain('Pesanan Selesai'); 
    });

    it('clears interval on unmount', () => {
        vi.useFakeTimers();
        const clearIntervalSpy = vi.spyOn(global, 'clearInterval');

        const wrapper = mount(OrderStatus, {
            props: {
                order: mockOrder
            },
            global: {
                stubs: {
                    Head: true,
                    Header: true
                }
            }
        });

        wrapper.unmount();
        expect(clearIntervalSpy).toHaveBeenCalled();
        
        vi.restoreAllMocks();
    });
});
