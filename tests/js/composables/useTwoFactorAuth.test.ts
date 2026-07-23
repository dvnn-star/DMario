import { describe, it, expect, beforeEach, vi, afterEach } from 'vitest';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';

vi.mock('@/routes/two-factor', () => ({
    qrCode: { url: () => '/two-factor/qr-code' },
    recoveryCodes: { url: () => '/two-factor/recovery-codes' },
    secretKey: { url: () => '/two-factor/secret-key' },
}));

describe('useTwoFactorAuth', () => {
    beforeEach(() => {
        global.fetch = vi.fn();
    });

    afterEach(() => {
        vi.clearAllMocks();
    });

    it('initializes with empty state', () => {
        const { qrCodeSvg, manualSetupKey, recoveryCodesList, errors, hasSetupData } = useTwoFactorAuth();
        
        expect(qrCodeSvg.value).toBeNull();
        expect(manualSetupKey.value).toBeNull();
        expect(recoveryCodesList.value).toEqual([]);
        expect(errors.value).toEqual([]);
        expect(hasSetupData.value).toBe(false);
    });

    it('fetches QR code successfully', async () => {
        const mockResponse = { svg: '<svg></svg>', url: 'some-url' };
        (global.fetch as any).mockResolvedValueOnce({
            ok: true,
            json: async () => mockResponse,
        });

        const { fetchQrCode, qrCodeSvg, errors } = useTwoFactorAuth();
        
        await fetchQrCode();
        
        expect(global.fetch).toHaveBeenCalledWith('/two-factor/qr-code', expect.any(Object));
        expect(qrCodeSvg.value).toBe('<svg></svg>');
        expect(errors.value).toEqual([]);
    });

    it('handles fetch QR code failure', async () => {
        (global.fetch as any).mockResolvedValueOnce({
            ok: false,
            status: 500,
        });

        const { fetchQrCode, qrCodeSvg, errors } = useTwoFactorAuth();
        
        await fetchQrCode();
        
        expect(qrCodeSvg.value).toBeNull();
        expect(errors.value).toContain('Failed to fetch QR code');
    });

    it('clears setup data correctly', () => {
        const { clearSetupData, manualSetupKey, qrCodeSvg, errors } = useTwoFactorAuth();
        
        manualSetupKey.value = 'some-key';
        qrCodeSvg.value = '<svg></svg>';
        errors.value = ['some error'];
        
        clearSetupData();
        
        expect(manualSetupKey.value).toBeNull();
        expect(qrCodeSvg.value).toBeNull();
        expect(errors.value).toEqual([]);
    });
});
