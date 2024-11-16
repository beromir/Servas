<script>
    import JetActionSection from '@/Jetstream/ActionSection.svelte';
    import JetButton from '@/Jetstream/Button.svelte';
    import JetConfirmsPassword from '@/Jetstream/ConfirmsPassword.svelte';
    import JetDangerButton from '@/Jetstream/DangerButton.svelte';
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.svelte';
    import JetInput from '@/Jetstream/Input.svelte';
    import JetInputError from '@/Jetstream/InputError.svelte';
    import JetLabel from '@/Jetstream/Label.svelte';
    import {page, useForm, router} from "@inertiajs/svelte";

    export let requiresConfirmation;

    let enabling = false;
    let confirming = false;
    let disabling = false;

    let qrCode = null;
    let setupKey = null;
    let recoveryCodes = [];

    let confirmationForm = useForm({
        code: '',
    });

    $: twoFactorEnabled = !enabling && $page.props.auth.user.two_factor_enabled;

    function enableTwoFactorAuthentication() {
        enabling = true

        router.post('/user/two-factor-authentication', {}, {
            preserveScroll: true,
            onSuccess: () => Promise.all([
                showQrCode(),
                showSetupKey(),
                showRecoveryCodes(),
            ]),
            onFinish: () => {
                enabling = false;
                confirming = requiresConfirmation;
            },
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

    function confirmTwoFactorAuthentication() {
        $confirmationForm.post('/user/confirmed-two-factor-authentication', {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                confirming = false;
                qrCode = null;
                $confirmationForm.reset();
                $confirmationForm.clearErrors();
            },
        });
    }

    function regenerateRecoveryCodes() {
        axios.post('/user/two-factor-recovery-codes')
            .then(response => {
                showRecoveryCodes()
            })
    }

    function disableTwoFactorAuthentication() {
        disabling = true

        router.delete('/user/two-factor-authentication', {
            preserveScroll: true,
            onSuccess: () => {
                disabling = false;
                confirming = false;
            },
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
        {#if twoFactorEnabled && !confirming}
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                You have enabled two factor authentication.
            </h3>

        {:else if confirming}
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Finish enabling two factor authentication.
            </h3>

        {:else}
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                You have not enabled two factor authentication.
            </h3>
        {/if}

        <div class="mt-3 max-w-xl text-sm text-gray-600 dark:text-gray-400">
            <p>
                When two factor authentication is enabled, you will be prompted for a secure, random token during
                authentication. You may retrieve this token from your phone's Google Authenticator application.
            </p>
        </div>

        {#if twoFactorEnabled}
            {#if qrCode}
                <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                    {#if confirming}
                        <p class="font-semibold">
                            To finish enabling two factor authentication, scan the following QR code using your phone's
                            authenticator application or enter the setup key and provide the generated OTP code.
                        </p>

                    {:else}
                        <p class="font-semibold">
                            Two factor authentication is now enabled. Scan the following QR code using your phone's
                            authenticator application or enter the setup key.
                        </p>
                    {/if}
                </div>

                <div class="mt-4">
                    {@html qrCode}
                </div>

                {#if confirming}
                    <div class="mt-4">
                        <JetLabel id="code" label="Code"/>

                        <JetInput id="code" type="text" name="code"
                                  class="block mt-1 w-1/2"
                                  inputmode="numeric"
                                  autofocus
                                  autocomplete="one-time-code"
                                  bind:value={$confirmationForm.code}/>

                        <JetInputError message={$page.props.errors?.confirmTwoFactorAuthentication?.code} class="mt-2"/>
                    </div>
                {/if}

                <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                    <p class="font-semibold">
                        Setup Key: <span class="select-all">{@html setupKey}</span>
                    </p>
                </div>
            {/if}

            {#if recoveryCodes.length > 0 && !confirming}
                <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                    <p class="font-semibold">
                        Store these recovery codes in a secure password manager. They can be used to recover access to
                        your account if your two factor authentication device is lost.
                    </p>
                </div>

                <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg select-all dark:bg-gray-950 dark:text-white">
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
                <JetConfirmsPassword on:confirmed={confirmTwoFactorAuthentication}>
                    {#if confirming}
                        <JetButton type="button" class="mr-3">Confirm</JetButton>
                    {/if}
                </JetConfirmsPassword>

                <JetConfirmsPassword on:confirmed={regenerateRecoveryCodes}>
                    {#if recoveryCodes.length > 0 && !confirming}
                        <JetSecondaryButton class="mr-3">
                            Regenerate Recovery Codes
                        </JetSecondaryButton>
                    {/if}
                </JetConfirmsPassword>

                <JetConfirmsPassword on:confirmed={showRecoveryCodes}>
                    {#if recoveryCodes.length === 0 && !confirming}
                        <JetSecondaryButton class="mr-3">
                            Show Recovery Codes
                        </JetSecondaryButton>
                    {/if}
                </JetConfirmsPassword>

                <JetConfirmsPassword on:confirmed={disableTwoFactorAuthentication}>
                    {#if confirming}
                        <JetSecondaryButton>Cancel</JetSecondaryButton>
                    {/if}
                </JetConfirmsPassword>

                <JetConfirmsPassword on:confirmed={disableTwoFactorAuthentication}>
                    {#if !confirming}
                        <JetDangerButton class={disabling ? 'opacity-25' : ''} disabled={disabling}>
                            Disable
                        </JetDangerButton>
                    {/if}
                </JetConfirmsPassword>
            {/if}
        </div>
    </svelte:fragment>
</JetActionSection>
