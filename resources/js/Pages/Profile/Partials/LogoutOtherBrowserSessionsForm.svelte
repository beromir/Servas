<script>
    // import JetActionMessage from '@/Jetstream/ActionMessage.svelte'
    import JetActionSection from '@/Jetstream/ActionSection.svelte'
    import JetButton from '@/Jetstream/Button.svelte'
    import JetDialogModal from '@/Jetstream/DialogModal.svelte'
    import JetInput from '@/Jetstream/Input.svelte'
    import JetInputError from '@/Jetstream/InputError.svelte'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.svelte'
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";

    export let sessions;

    let confirmingLogout = false;
    let passwordInput;

    let form = useForm({
        password: '',
    });

    function confirmLogout() {
        confirmingLogout = true;

        setTimeout(() => passwordInput.focus(), 250);
    }

    function logoutOtherBrowserSessions() {
        $form.delete(route('other-browser-sessions.destroy'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => passwordInput.focus(),
            onFinish: () => $form.reset(),
        });
    }

    function closeModal() {
        confirmingLogout = false;

        $form.reset();
    }
</script>

<JetActionSection>
    <svelte:fragment slot="title">
        Browser Sessions
    </svelte:fragment>

    <svelte:fragment slot="description">
        Manage and log out your active sessions on other browsers and devices.
    </svelte:fragment>

    <svelte:fragment slot="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your
            recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has
            been compromised, you should also update your password.
        </div>

        <!-- Other Browser Sessions -->
        {#if sessions.length > 0}
            <div class="mt-5 space-y-6">
                {#each sessions as session, i (i)}
                    <div class="flex items-center">
                        <div>
                            {#if session.agent.is_desktop}
                                <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor" class="size-8 text-gray-500 dark:text-gray-400">
                                    <path
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>

                            {:else}
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2"
                                     stroke="currentColor"
                                     fill="none" stroke-linecap="round" stroke-linejoin="round"
                                     class="size-8 text-gray-500">
                                    <path d="M0 0h24v24H0z" stroke="none"></path>
                                    <rect x="7" y="4" width="10" height="16" rx="1"></rect>
                                    <path d="M11 5h2M12 17v.01"></path>
                                </svg>
                            {/if}
                        </div>

                        <div class="ml-3">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {session.agent.platform ? session.agent.platform : 'Unknown'}
                                - {session.agent.browser ? session.agent.browser : 'Unknown'}
                            </div>

                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {session.ip_address},

                                    {#if session.is_current_device}
                                        <span class="text-green-500 font-semibold">This device</span>
                                    {:else}
                                        <span>Last active {session.last_active}</span>
                                    {/if}
                                </div>
                            </div>
                        </div>
                    </div>
                {/each}
            </div>
        {/if}

        <div class="flex items-center mt-5">
            <JetButton on:clicked={confirmLogout}>
                Log Out Other Browser Sessions
            </JetButton>

            <!--
            <jet-action-message :on="form.recentlySuccessful" class="ml-3">
                Done.
            </jet-action-message>
            -->
        </div>

        <!-- Log Out Other Devices Confirmation Modal -->
        <JetDialogModal show={confirmingLogout} on:close={closeModal}>
            <svelte:fragment slot="title">
                Log Out Other Browser Sessions
            </svelte:fragment>

            <svelte:fragment slot="content">
                Please enter your password to confirm you would like to log out of your other browser
                sessions across
                all of your devices.

                <div class="mt-4">
                    <JetInput type="password" class="mt-1 block w-3/4" placeholder="Password"
                              bind:this={passwordInput} bind:value={$form.password}/>

                    <JetInputError message={$form.errors.password} class="mt-2"/>
                </div>
            </svelte:fragment>

            <svelte:fragment slot="footer">
                <JetSecondaryButton on:clicked={closeModal}>
                    Cancel
                </JetSecondaryButton>

                <JetButton class="ml-3" on:clicked={logoutOtherBrowserSessions}>
                    Log Out Other Browser Sessions
                </JetButton>
            </svelte:fragment>
        </JetDialogModal>
    </svelte:fragment>
</JetActionSection>
