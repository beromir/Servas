<script>
    import JetActionSection from '@/Jetstream/ActionSection.svelte';
    import JetButton from '@/Jetstream/Button.svelte';
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.svelte';
    import JetDangerButton from '@/Jetstream/DangerButton.svelte';
    import JetDialogModal from '@/Jetstream/DialogModal.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import JetInputError from '@/Jetstream/InputError.svelte';
    import JetLabel from '@/Jetstream/Label.svelte';
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.svelte';
    import JetSectionBorder from '@/Jetstream/SectionBorder.svelte';
    import {page, router, useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";
    import Input from "@/Components/Input.svelte";

    let { tokens, availablePermissions, defaultPermissions } = $props();

    let displayingToken = $state(false);
    let managingPermissionsFor = $state(null);
    let apiTokenBeingDeleted = $state(null);

    let createApiTokenForm = useForm({
        name: '',
        permissions: defaultPermissions,
    });

    let updateApiTokenForm = useForm({
        permissions: [],
    });

    function createApiToken() {
        $createApiTokenForm.post(route('api-tokens.store'), {
            preserveScroll: true,
            onSuccess: () => {
                displayingToken = true;
                $createApiTokenForm.reset();
            }
        });
    }

    function manageApiTokenPermissions(token) {
        $updateApiTokenForm.permissions = token.abilities;

        managingPermissionsFor = token
    }

    function updateApiToken() {
        $updateApiTokenForm.put(route('api-tokens.update', managingPermissionsFor), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => (managingPermissionsFor = null),
        });
    }

    function confirmApiTokenDeletion(token) {
        apiTokenBeingDeleted = token;
    }

    function deleteApiToken() {
        router.delete(route('api-tokens.destroy', apiTokenBeingDeleted), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => (apiTokenBeingDeleted = null),
        });
    }
</script>

<div>
    <!-- Generate API Token -->
    <JetFormSection submitted={createApiToken}>
        {#snippet title()}

                Create API Token

            {/snippet}

        {#snippet description()}

                API tokens allow third-party services to authenticate with our application on your behalf.

            {/snippet}

        {#snippet form()}

                <!-- Token Name -->
                <div class="col-span-6 sm:col-span-4">
                    <JetLabel id="name" label="Name"/>
                    <Input id="name" autofocus bind:value={$createApiTokenForm.name} class="mt-1"/>
                    <JetInputError message={$createApiTokenForm.errors.name} class="mt-2"/>
                </div>

                <!-- Token Permissions -->
                {#if availablePermissions.length > 0}
                    <div class="col-span-6">
                        <JetLabel id="permissions" label="Permissions"/>

                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            {#each availablePermissions as permission (permission)}
                                <label class="flex items-center">
                                    <input value={permission} bind:group={$createApiTokenForm.permissions} type="checkbox"
                                           class="rounded-sm border-gray-400 text-primary-600 shadow-xs dark:bg-gray-800 dark:border-gray-600"/>
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">{permission}</span>
                                </label>
                            {/each}
                        </div>
                    </div>
                {/if}

            {/snippet}

        {#snippet actions()}

                <!--
                <jet-action-message :on="createApiTokenForm.recentlySuccessful" class="mr-3">
                    Created.
                </jet-action-message>
                -->

                <JetButton class={$createApiTokenForm.processing ? 'opacity-25' : ''} type="submit"
                           disabled={$createApiTokenForm.processing}>
                    Create
                </JetButton>

            {/snippet}
    </JetFormSection>

    {#if tokens.length > 0}
        <JetSectionBorder/>

        <!-- Manage API Tokens -->
        <div class="mt-10 sm:mt-0">
            <JetActionSection>
                {#snippet title()}

                        Manage API Tokens

                            {/snippet}

                {#snippet description()}

                        You may delete any of your existing tokens if they are no longer needed.

                            {/snippet}

                <!-- API Token List -->
                {#snippet content()}

                        <div class="space-y-6">
                            {#each tokens as token (token.id)}
                                <div class="flex items-center justify-between">
                                    <div class="truncate dark:text-white">
                                        {token.name}
                                    </div>

                                    <div class="flex items-center">
                                        {#if true}
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                Last used {token.last_used_ago}
                                            </div>
                                        {/if}

                                        {#if availablePermissions.length > 0}
                                            <button
                                                class="cursor-pointer ml-6 text-sm text-gray-500 underline dark:text-gray-400"
                                                onclick={() => manageApiTokenPermissions(token)}>
                                                Permissions
                                            </button>
                                        {/if}

                                        <button class="cursor-pointer ml-6 text-sm text-red-500 dark:text-red-400"
                                                onclick={() => confirmApiTokenDeletion(token)}>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            {/each}
                        </div>

                            {/snippet}
            </JetActionSection>
        </div>
    {/if}

    <!-- Token Value Modal -->
    <JetDialogModal show={displayingToken} close={() => displayingToken = false}>
        {#snippet title()}

                API Token

            {/snippet}

        {#snippet content()}

                <div class="dark:text-white">
                    Please copy your new API token. For your security, it won't be shown again.
                </div>

                {#if $page.props.jetstream.flash.token}
                    <div class="mt-4 bg-gray-100 px-4 py-2 rounded-sm font-mono text-sm text-gray-600 select-all dark:bg-gray-950 dark:text-gray-300">
                        {$page.props.jetstream.flash.token}
                    </div>
                {/if}

            {/snippet}

        {#snippet footer()}

                <JetSecondaryButton clicked={() => displayingToken = false}>
                    Close
                </JetSecondaryButton>

            {/snippet}
    </JetDialogModal>

    <!-- API Token Permissions Modal -->
    <JetDialogModal show={managingPermissionsFor} close={() => managingPermissionsFor = null}>
        {#snippet title()}

                API Token Permissions

            {/snippet}

        {#snippet content()}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {#each availablePermissions as permission (permission)}
                        <label class="flex items-center">
                            <input value={permission} bind:group={$updateApiTokenForm.permissions} type="checkbox"
                                   class="rounded-sm border-gray-300 text-primary-600 shadow-xs dark:bg-gray-800 dark:border-gray-600"/>
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">{permission}</span>
                        </label>
                    {/each}
                </div>

            {/snippet}

        {#snippet footer()}

                <JetSecondaryButton clicked={() => managingPermissionsFor = null}>
                    Cancel
                </JetSecondaryButton>

                <JetButton clicked={updateApiToken}
                           class={$updateApiTokenForm.processing ? 'ml-3 opacity-25' : 'ml-3'}
                           disabled={$updateApiTokenForm.processing}>
                    Save
                </JetButton>

            {/snippet}
    </JetDialogModal>

    <!-- Delete Token Confirmation Modal -->
    <JetConfirmationModal show={apiTokenBeingDeleted} close={() => apiTokenBeingDeleted = null}>
        {#snippet title()}

                Delete API Token

            {/snippet}

        {#snippet content()}

                Are you sure you would like to delete this API token?

            {/snippet}

        {#snippet footer()}

                <JetSecondaryButton clicked={() => apiTokenBeingDeleted = null}>
                    Cancel
                </JetSecondaryButton>

                <JetDangerButton clicked={deleteApiToken} class="ml-3 disabled:opacity-25">
                    Delete
                </JetDangerButton>

            {/snippet}
    </JetConfirmationModal>
</div>
