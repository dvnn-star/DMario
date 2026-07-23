import { config } from '@vue/test-utils';
import { vi } from 'vitest';

// Mock inertia Link and Head components
config.global.stubs = {
    Link: true,
    Head: true,
};

// Mock ziggy-js route function globally
(global as any).route = (name: string, params: any) => {
    return `/${name}`;
};

// Mock inertia useForm
vi.mock('@inertiajs/vue3', async (importOriginal) => {
    const actual = await importOriginal<typeof import('@inertiajs/vue3')>();
    return {
        ...actual,
        Head: {
            name: 'Head',
            render: () => null
        },
        useForm: (data: any) => ({
            ...data,
            post: vi.fn(),
            put: vi.fn(),
            delete: vi.fn(),
            reset: vi.fn(),
            clearErrors: vi.fn(),
            errors: {},
            processing: false,
        }),
    };
});

// Mock ziggy-js route module
vi.mock('ziggy-js', () => ({
    route: (name: string, params: any) => `/${name}`
}));
