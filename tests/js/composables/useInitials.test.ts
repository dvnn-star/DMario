import { describe, it, expect } from 'vitest';
import { useInitials } from '@/composables/useInitials';

describe('useInitials', () => {
    it('returns initials for a full name', () => {
        const { getInitials } = useInitials();
        expect(getInitials('John Doe')).toBe('JD');
    });

    it('returns first letter for a single name', () => {
        const { getInitials } = useInitials();
        expect(getInitials('John')).toBe('J');
    });

    it('handles extra spaces', () => {
        const { getInitials } = useInitials();
        expect(getInitials('  John   Doe  ')).toBe('JD');
    });

    it('returns empty string for empty input', () => {
        const { getInitials } = useInitials();
        expect(getInitials('')).toBe('');
    });
});
