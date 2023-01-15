<script>
    import {createEventDispatcher} from 'svelte';
    import Button from "@/Components/Buttons/Button.svelte";
    import Input from '@/Components/Inputs/Input.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";
    import {refreshGroups} from "@/stores";
    import GroupSelectMenu from "@/Partials/GroupSelectMenu.svelte";

    const dispatch = createEventDispatcher();

    export let modeIsEdit = false;
    let group = {};
    let input;
    let groupSelectMenu;
    let selectedGroups = [];

    let form = useForm({
        title: null,
        parentGroupId: null,
    });

    $: $form.parentGroupId = selectedGroups[0];

    function createGroup() {
        $form.clearErrors();
        $form.post(route('groups.store'), {
            preserveScroll: true,
            onSuccess: () => {
                $form.reset();
                groupSelectMenu.reset();
                $refreshGroups = true;
            },
        });
    }

    function editGroup() {
        $form.clearErrors();
        $form.patch(route('groups.update', group.id), {
            preserveScroll: true,
            onSuccess: () => {
                endEditMode();
                $refreshGroups = true;
            },
        });
    }

    function endEditMode() {
        group = {};
        $form.clearErrors();
        $form.reset();
        groupSelectMenu.reset();
        modeIsEdit = false;
    }

    export function initEditMode(groupToEdit) {
        $form.clearErrors();
        group = groupToEdit;
        $form.title = groupToEdit.title;
        groupSelectMenu.setSelectedGroups([group.parentGroupId]);
        modeIsEdit = true;
    }

    export const focus = () => input.focus();
</script>

<svelte:window on:groupDeletionWasSuccessful={endEditMode}/>

<form on:submit|preventDefault={modeIsEdit ? editGroup : createGroup}
      class="space-y-3 sm:flex sm:space-x-3 sm:space-y-0">
    <div class="w-full sm:max-w-xs">
        <Input placeholder="Enter group name..." bind:value={$form.title} bind:this={input}/>
    </div>

    <Button on:clicked={groupSelectMenu.openModal}
            title={selectedGroups.length ? `${selectedGroups.length} Group selected` : 'Select group'} color="white"
            class="justify-center w-full whitespace-nowrap focus:ring-offset-gray-100 sm:w-auto"/>

    <Button title={modeIsEdit ? 'Save changes' : 'Create group'} type="submit"
            class="justify-center w-full whitespace-nowrap focus:ring-offset-gray-100 sm:w-auto"/>


    {#if modeIsEdit}
        <div class="flex items-center">
            <button on:click={endEditMode} type="button"
                    class="w-full text-gray-700 text-sm focus:outline-none focus:ring-primary-400 focus:ring-offset-gray-100 focus:ring-2 focus:ring-offset-2 sm:ml-3">
                Cancel
            </button>
        </div>
    {/if}

    {#if $form.errors.title}
        <div class="inline-flex ml-4 items-center text-yellow-800 whitespace-nowrap">{$form.errors.title}</div>
    {/if}
    {#if $form.errors.parentGroupId}
        <div class="inline-flex ml-4 items-center text-yellow-800 whitespace-nowrap">{$form.errors.parentGroupId}</div>
    {/if}
</form>

<GroupSelectMenu bind:this={groupSelectMenu} bind:selectedGroups/>
