<script>
    import JetActionSection from '@/Jetstream/ActionSection.svelte';
    import JetButton from '@/Jetstream/Button.svelte';
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.svelte';
    import JetDangerButton from '@/Jetstream/DangerButton.svelte';
    import JetDialogModal from '@/Jetstream/DialogModal.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import JetInput from '@/Jetstream/Input.svelte';
    import JetInputError from '@/Jetstream/InputError.svelte';
    import JetLabel from '@/Jetstream/Label.svelte';
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.svelte';
    import JetSectionBorder from '@/Jetstream/SectionBorder.svelte';
    import {page, router, useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";

    export let tokens;
    export let availablePermissions;
    export let defaultPermissions;

    let displayingToken = false;
    let managingPermissionsFor = null;
    let apiTokenBeingDeleted = null;

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
    <JetFormSection on:submitted={createApiToken}>
        <svelte:fragment slot="title">
            Create API Token
        </svelte:fragment>

        <svelte:fragment slot="description">
            API tokens allow third-party services to authenticate with our application on your behalf.
        </svelte:fragment>

        <svelte:fragment slot="form">
            <!-- Token Name -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel id="name" label="Name"/>
                <JetInput id="name" type="text" class="mt-1 block w-full" bind:value={$createApiTokenForm.name}
                          autofocus/>
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
                                       class="rounded border-gray-400 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:ring-offset-gray-800"/>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">{permission}</span>
                            </label>
                        {/each}
                    </div>
                </div>
            {/if}
        </svelte:fragment>

        <svelte:fragment slot="actions">
            <!--
            <jet-action-message :on="createApiTokenForm.recentlySuccessful" class="mr-3">
                Created.
            </jet-action-message>
            -->

            <JetButton class={$createApiTokenForm.processing ? 'opacity-25' : ''} type="submit"
                       disabled={$createApiTokenForm.processing}>
                Create
            </JetButton>
        </svelte:fragment>
    </JetFormSection>

    {#if tokens.length > 0}
        <JetSectionBorder/>

        <!-- Manage API Tokens -->
        <div class="mt-10 sm:mt-0">
            <JetActionSection>
                <svelte:fragment slot="title">
                    Manage API Tokens
                </svelte:fragment>

                <svelte:fragment slot="description">
                    You may delete any of your existing tokens if they are no longer needed.
                </svelte:fragment>

                <!-- API Token List -->
                <svelte:fragment slot="content">
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
                                            on:click={() => manageApiTokenPermissions(token)}>
                                            Permissions
                                        </button>
                                    {/if}

                                    <button class="cursor-pointer ml-6 text-sm text-red-500 dark:text-red-400"
                                            on:click={() => confirmApiTokenDeletion(token)}>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        {/each}
                    </div>
                </svelte:fragment>
            </JetActionSection>
        </div>
    {/if}

    <!-- Token Value Modal -->
    <JetDialogModal show={displayingToken} on:close={() => displayingToken = false}>
        <svelte:fragment slot="title">
            API Token
        </svelte:fragment>

        <svelte:fragment slot="content">
            <div class="dark:text-white">
                Please copy your new API token. For your security, it won't be shown again.
            </div>

            {#if $page.props.jetstream.flash.token}
                <div class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-600 select-all dark:bg-gray-950 dark:text-gray-300">
                    {$page.props.jetstream.flash.token}
                </div>
            {/if}
        </svelte:fragment>

        <svelte:fragment slot="footer">
            <JetSecondaryButton on:clicked={() => displayingToken = false}>
                Close
            </JetSecondaryButton>
        </svelte:fragment>
    </JetDialogModal>

    <!-- API Token Permissions Modal -->
    <JetDialogModal show={managingPermissionsFor} on:close={() => managingPermissionsFor = null}>
        <svelte:fragment slot="title">
            API Token Permissions
        </svelte:fragment>

        <svelte:fragment slot="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {#each availablePermissions as permission (permission)}
                    <label class="flex items-center">
                        <input value={permission} bind:group={$updateApiTokenForm.permissions} type="checkbox"
                               class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:ring-offset-gray-800"/>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">{permission}</span>
                    </label>
                {/each}
            </div>
        </svelte:fragment>

        <svelte:fragment slot="footer">
            <JetSecondaryButton on:clicked={() => managingPermissionsFor = null}>
                Cancel
            </JetSecondaryButton>

            <JetButton on:clicked={updateApiToken}
                       class={$updateApiTokenForm.processing ? 'ml-3 opacity-25' : 'ml-3'}
                       disabled={$updateApiTokenForm.processing}>
                Save
            </JetButton>
        </svelte:fragment>
    </JetDialogModal>

    <!-- Delete Token Confirmation Modal -->
    <JetConfirmationModal show={apiTokenBeingDeleted} on:close={() => apiTokenBeingDeleted = null}>
        <svelte:fragment slot="title">
            Delete API Token
        </svelte:fragment>

        <svelte:fragment slot="content">
            Are you sure you would like to delete this API token?
        </svelte:fragment>

        <svelte:fragment slot="footer">
            <JetSecondaryButton on:clicked={() => apiTokenBeingDeleted = null}>
                Cancel
            </JetSecondaryButton>

            <JetDangerButton on:clicked={deleteApiToken} class="ml-3 disabled:opacity-25">
                Delete
            </JetDangerButton>
        </svelte:fragment>
    </JetConfirmationModal>
</div>
