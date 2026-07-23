import { describe, it, expect, beforeEach, vi, afterEach } from 'vitest';
import { useAppearance, updateTheme } from '@/composables/useAppearance';
import { createApp } from 'vue';

describe('useAppearance', () => {
    let mockMatchMedia: any;

    beforeEach(() => {
        // Mock localStorage
        const localStorageMock = (() => {
            let store: Record<string, string> = {};
            return {
                getItem(key: string) {
                    return store[key] || null;
                },
                setItem(key: string, value: string) {
                    store[key] = value.toString();
                },
                clear() {
                    store = {};
                }
            };
        })();
        Object.defineProperty(window, 'localStorage', { value: localStorageMock });

        // Mock document.cookie
        Object.defineProperty(document, 'cookie', { writable: true, value: '' });

        // Spy on classList.toggle
        vi.spyOn(document.documentElement.classList, 'toggle');

        // Mock matchMedia
        mockMatchMedia = vi.fn().mockImplementation(query => ({
            matches: false,
            media: query,
            onchange: null,
            addListener: vi.fn(), // Deprecated
            removeListener: vi.fn(), // Deprecated
            addEventListener: vi.fn(),
            removeEventListener: vi.fn(),
            dispatchEvent: vi.fn(),
        }));
        Object.defineProperty(window, 'matchMedia', {
            writable: true,
            value: mockMatchMedia
        });
    });

    afterEach(() => {
        vi.clearAllMocks();
        localStorage.clear();
    });

    it('updateTheme applies dark class when value is dark', () => {
        updateTheme('dark');
        expect(document.documentElement.classList.toggle).toHaveBeenCalledWith('dark', true);
    });

    it('updateTheme removes dark class when value is light', () => {
        updateTheme('light');
        expect(document.documentElement.classList.toggle).toHaveBeenCalledWith('dark', false);
    });

    it('updateAppearance updates local storage and cookies', () => {
        let result: any;
        const app = createApp({
            setup() {
                result = useAppearance();
                return () => {};
            }
        });
        
        app.mount(document.createElement('div'));

        const { updateAppearance } = result;
        updateAppearance('dark');

        expect(localStorage.getItem('appearance')).toBe('dark');
        expect(document.cookie).toContain('appearance=dark');
        expect(document.documentElement.classList.toggle).toHaveBeenCalledWith('dark', true);
        
        app.unmount();
    });
});
