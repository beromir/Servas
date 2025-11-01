<script>
    import { run } from 'svelte/legacy';

    import Modal from "@/Components/Modals/Modal.svelte";
    import Container from "@/Components/FormLayouts/Modals/Container.svelte";
    import Input from "@/Components/FormLayouts/Modals/Input.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import {useForm} from "@inertiajs/svelte";
    import {route, dispatchCustomEvent, toggleValueInArray} from '@/utils';
    import {refreshGroups} from "@/stores";
    import GroupSelectMenu from "@/Partials/GroupSelectMenu.svelte";
    import {getTagIdsFromArray} from "@/utils/tag.js";
    import PlusCircle from "@/Heroicons/Mini/PlusCircle.svelte";
    import XMark from "@/Heroicons/Micro/XMark.svelte";
    import QueryOption from "@/Components/QueryOptions/QueryOption.svelte";
    import Minus from "@/Heroicons/Mini/Minus.svelte";
    import Plus from "@/Heroicons/Mini/Plus.svelte";

    let showModal = $state(false);
    let isEditing = $state(false);
    let group = $state(null);
    let groupSelectMenu = $state();
    let selectedGroups = $state([]);
    let showSmartGroupSettings = $state(false);

    let form = useForm({
        title: null,
        parentGroupId: null,
        orTags: [],
        andTags: [],
        notTags: [],
    });

    run(() => {
        $form.parentGroupId = selectedGroups[0] ?? null;
    });

    export function showCreationView(parentGroupId = null) {
        if (parentGroupId !== null) {
            groupSelectMenu.setSelectedGroups([parentGroupId]);
        } else {
            groupSelectMenu.reset();
        }

        isEditing && reset();

        group = {
            orTags: [],
            andTags: [],
            notTags: [],
        };

        showModal = true;
    }

    function showEditingView(groupToEdit) {
        reset();

        group = groupToEdit;

        $form.title = group.title;
        groupSelectMenu.setSelectedGroups([group.parentGroupId]);

        isEditing = true;

        if (group.orTags.length || group.andTags.length || group.notTags.length) {
            showSmartGroupSettings = true;
        }

        showModal = true;
    }

    function createGroup() {
        // Only save the tag IDs
        $form.orTags = getTagIdsFromArray(group.orTags);
        $form.andTags = getTagIdsFromArray(group.andTags);
        $form.notTags = getTagIdsFromArray(group.notTags);

        $form.clearErrors();
        $form.post(route('groups.store'), {
            preserveScroll: true,
            onSuccess: () => {
                refreshGroups.update();
                showModal = false;
                reset();
            },
        });
    }

    function updateGroup() {
        // Only save the tag IDs
        $form.orTags = getTagIdsFromArray(group.orTags);
        $form.andTags = getTagIdsFromArray(group.andTags);
        $form.notTags = getTagIdsFromArray(group.notTags);

        $form.clearErrors();
        $form.patch(route('groups.update', group.id), {
            preserveScroll: true,
            onSuccess: () => {
                refreshGroups.update();
                showModal = false;
                reset();
            },
        });
    }

    function reset() {
        $form.reset();
        groupSelectMenu.reset();
        isEditing = false;
        group = null;
        selectedGroups = [];
        showSmartGroupSettings = false;
    }
</script>

<svelte:window oneditGroup={(event) => showEditingView(event.detail)}
               oncreateGroup={(event) => showCreationView(event.detail)}/>

<Modal title={isEditing ? 'Edit group' : 'Create group'} showFooterMenuOnMobile={false} bind:showModal>
    {#snippet mobilePrimaryAction()}
    
            <button onclick={isEditing ? updateGroup : createGroup}
                    class="text-right text-primary-600 font-medium focus:outline-none sm:hidden dark:text-gray-100" type="button">
                {isEditing ? 'Edit' : 'Create'}
            </button>
        
    {/snippet}

    <Container>
        <Input label="Title" name="title" isFirst={true} bind:value={$form.title}
               error={$form.errors.title} autofocus={!isEditing}/>

        <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5 dark:border-gray-700">
            <span class="block text-sm text-left font-medium text-gray-700 sm:mt-px sm:pt-2 dark:text-gray-200">
                Groups
            </span>
            <div class="max-w-xl mt-1 relative sm:mt-0 sm:col-span-2">
                <Button on:clicked={groupSelectMenu.openModal}
                        title={selectedGroups.length ? `${selectedGroups.length} Group${selectedGroups.length > 1 ? 's' : ''} selected` : 'Select group'}
                        color="white"/>
            </div>
        </div>

        <div class="sm:border-t sm:border-gray-200 sm:pt-5 dark:border-gray-700">
            <button onclick={() => showSmartGroupSettings = !showSmartGroupSettings} type="button"
                    class="flex justify-between items-center w-full group">
                <span class="text-left">
                    <span class="block text-gray-800 font-medium dark:text-gray-100">Smart Group settings</span>
                    <span class="block -mt-0.5 text-sm text-gray-500 dark:text-gray-400">Show tagged links in the group</span>
                </span>

                {#if showSmartGroupSettings}
                    <Minus className="text-gray-600 group-hover:text-gray-900 dark:text-gray-200 dark:group-hover:text-white"/>

                {:else}
                    <Plus className="text-gray-600 group-hover:text-gray-900 dark:text-gray-200 dark:group-hover:text-white"/>
                {/if}
            </button>

            {#if showSmartGroupSettings}
                <div class="mt-4 grid gap-3">
                    <QueryOption bind:tags={group.orTags} optionMode="or" title="any of these"/>
                    <QueryOption bind:tags={group.andTags} optionMode="and" title="all of these"/>
                    <QueryOption bind:tags={group.notTags} optionMode="not" title="none of these"/>
                </div>
            {/if}
        </div>
    </Container>

    {#snippet footer()}
    
            <Button on:clicked={() => showModal = false} title="Cancel" color="white"
                    class="focus:ring-offset-gray-50"/>
            <Button on:clicked={isEditing ? updateGroup : createGroup}
                    title={isEditing ? 'Edit group' : 'Create group'}
                    class="focus:ring-offset-gray-50"/>
        
    {/snippet}
</Modal>

<GroupSelectMenu bind:this={groupSelectMenu} bind:selectedGroups/>
