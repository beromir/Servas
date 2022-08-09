<script>
    import JetActionSection from '@/Jetstream/ActionSection.svelte';
    import JetButton from '@/Jetstream/Button.svelte';
    import JetConfirmsPassword from '@/Jetstream/ConfirmsPassword.svelte';
    import JetDangerButton from '@/Jetstream/DangerButton.svelte';
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.svelte';
    import {page} from "@inertiajs/inertia-svelte";
    import {Inertia} from "@inertiajs/inertia";

    let enabling = false;
    let disabling = false;

    let qrCode = null;
    let setupKey = null;
    let recoveryCodes = [];

    $: twoFactorEnabled = !enabling && $page.props.user.two_factor_enabled;

    function enableTwoFactorAuthentication() {
        enabling = true

        Inertia.post('/user/two-factor-authentication', {}, {
            preserveScroll: true,
            onSuccess: () => Promise.all([
                showQrCode(),
                showSetupKey(),
                showRecoveryCodes(),
            ]),
            onFinish: () => (enabling = false),
        })
    }

    function showQrCode() {
        return axios.get('/user/two-factor-qr-code')
            .then(response => {
                qrCode = response.data.svg
            })
    }

    function showSetupKey() {
        return axios.get('/user/two-factor-secret-key')
            .then(response => {
                setupKey = response.data.secretKey;
            });
    }

    function showRecoveryCodes() {
        return axios.get('/user/two-factor-recovery-codes')
            .then(response => {
                recoveryCodes = response.data
            })
    }

    function regenerateRecoveryCodes() {
        axios.post('/user/two-factor-recovery-codes')
            .then(response => {
                showRecoveryCodes()
            })
    }

    function disableTwoFactorAuthentication() {
        disabling = true

        Inertia.delete('/user/two-factor-authentication', {
            preserveScroll: true,
            onSuccess: () => (disabling = false),
        });
    }
</script>

<JetActionSection>
    <svelte:fragment slot="title">
        Two Factor Authentication
    </svelte:fragment>

    <svelte:fragment slot="description">
        Add additional security to your account using two factor authentication.
    </svelte:fragment>

    <svelte:fragment slot="content">
        {#if twoFactorEnabled}
            <h3 class="text-lg font-medium text-gray-900">
                You have enabled two factor authentication.
            </h3>

        {:else}
            <h3 class="text-lg font-medium text-gray-900" v-else>
                You have not enabled two factor authentication.
            </h3>
        {/if}

        <div class="mt-3 max-w-xl text-sm text-gray-600">
            <p>
                When two factor authentication is enabled, you will be prompted for a secure, random token during
                authentication. You may retrieve this token from your phone's Google Authenticator application.
            </p>
        </div>

        {#if twoFactorEnabled}
            {#if qrCode}
                <div class="mt-4 max-w-xl text-sm text-gray-600">
                    <p class="font-semibold">
                        Two factor authentication is now enabled. Scan the following QR code using your phone's
                        authenticator application or enter the setup key.
                    </p>
                </div>

                <div class="mt-4">
                    {@html qrCode}
                </div>

                <div class="mt-4 max-w-xl text-sm text-gray-600">
                    <p class="font-semibold">
                        Setup Key: <span class="select-all">{@html setupKey}</span>
                    </p>
                </div>
            {/if}

            {#if recoveryCodes.length > 0}
                <div class="mt-4 max-w-xl text-sm text-gray-600">
                    <p class="font-semibold">
                        Store these recovery codes in a secure password manager. They can be used to recover access to
                        your account if your two factor authentication device is lost.
                    </p>
                </div>

                <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg select-all">
                    {#each recoveryCodes as code (code)}
                        {code}<br/>
                    {/each}
                </div>
            {/if}
        {/if}

        <div class="mt-5">
            {#if !twoFactorEnabled}
                <JetConfirmsPassword on:confirmed={enableTwoFactorAuthentication}>
                    <JetButton type="button" class={enabling ? 'opacity-25' : ''} disabled={enabling}>
                        Enable
                    </JetButton>
                </JetConfirmsPassword>

            {:else}
                <JetConfirmsPassword on:confirmed={regenerateRecoveryCodes}>
                    {#if recoveryCodes.length > 0}
                        <JetSecondaryButton class="mr-3">
                            Regenerate Recovery Codes
                        </JetSecondaryButton>
                    {/if}
                </JetConfirmsPassword>

                <JetConfirmsPassword on:confirmed={showRecoveryCodes}>
                    {#if recoveryCodes.length === 0}
                        <JetSecondaryButton class="mr-3">
                            Show Recovery Codes
                        </JetSecondaryButton>
                    {/if}
                </JetConfirmsPassword>

                <JetConfirmsPassword on:confirmed={disableTwoFactorAuthentication}>
                    <JetDangerButton class={disabling ? 'opacity-25' : ''} disabled={disabling}>
                        Disable
                    </JetDangerButton>
                </JetConfirmsPassword>
            {/if}
        </div>
    </svelte:fragment>
</JetActionSection>
