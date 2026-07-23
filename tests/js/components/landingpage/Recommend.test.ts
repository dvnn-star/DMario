import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import Recommend from '@/components/landingpage/Recommend.vue';

describe('Recommend.vue', () => {
    it('renders empty state when no bestSellers are provided', () => {
        const wrapper = mount(Recommend, {
            props: {
                bestSellers: []
            }
        });

        expect(wrapper.text()).toContain('Belum ada menu rekomendasi yang ditampilkan saat ini.');
    });

    it('renders bestSellers when provided', () => {
        const items = [
            {
                id: 1,
                title: 'Nasi Goreng',
                tag: 'Popular',
                description: 'Delicious fried rice',
                price: 'Rp. 25.000',
                image: '/images/nasi-goreng.jpg'
            },
            {
                id: 2,
                title: 'Mie Goreng',
                tag: 'New',
                description: 'Delicious fried noodles',
                price: 'Rp. 20.000',
                image: '/images/mie-goreng.jpg'
            }
        ];

        const wrapper = mount(Recommend, {
            props: {
                bestSellers: items
            }
        });

        expect(wrapper.text()).not.toContain('Belum ada menu rekomendasi yang ditampilkan saat ini.');
        expect(wrapper.text()).toContain('Nasi Goreng');
        expect(wrapper.text()).toContain('Mie Goreng');
        expect(wrapper.text()).toContain('Rp. 25.000');
        expect(wrapper.text()).toContain('Rp. 20.000');
    });
});
