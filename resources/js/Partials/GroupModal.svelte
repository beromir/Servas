<script>
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

    let showModal = false;
    let isEditing = false;
    let group = null;
    let groupSelectMenu;
    let selectedGroups = [];

    let form = useForm({
        title: null,
        parentGroupId: null,
        orTags: [],
        andTags: [],
        notTags: [],
    });

    $: $form.parentGroupId = selectedGroups[0] ?? null;

    export function showCreationView(parentGroupId = null) {
        group = {
            orTags: [],
            andTags: [],
            notTags: [],
        };

        if (parentGroupId !== null) {
            groupSelectMenu.setSelectedGroups([parentGroupId]);
        } else {
            groupSelectMenu.reset();
        }

        isEditing && reset();

        showModal = true;
    }

    function showEditingView(groupToEdit) {
        reset();

        group = groupToEdit;

        $form.title = group.title;
        groupSelectMenu.setSelectedGroups([group.parentGroupId]);

        isEditing = true;

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
    }

    function handleSelectTagsButtonClick(option, tags) {
        dispatchCustomEvent('tags.select', {tags: tags});

        window.addEventListener('tags.selected', (e) => {
            switch (option) {
                case 'or':
                    group.orTags = e.detail;
                    break;
                case 'and':
                    group.andTags = e.detail;
                    break;
                case 'not':
                    group.notTags = e.detail;
            }
        }, {once: true});
    }
</script>

<svelte:window on:editGroup={(event) => showEditingView(event.detail)}
               on:createGroup={(event) => showCreationView(event.detail)}/>

<Modal title={isEditing ? 'Edit group' : 'Create group'} showFooterMenuOnMobile={false} bind:showModal>
    <svelte:fragment slot="mobilePrimaryAction">
        <button on:click={isEditing ? updateGroup : createGroup}
                class="text-right text-primary-600 font-medium focus:outline-none sm:hidden" type="button">
            {isEditing ? 'Edit' : 'Create'}
        </button>
    </svelte:fragment>

    <Container>
        <Input label="Title" name="title" isFirst={true} bind:value={$form.title}
               error={$form.errors.title} autofocus={!isEditing}/>

        <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
            <span class="block text-sm text-left font-medium text-gray-700 sm:mt-px sm:pt-2">
                Groups
            </span>
            <div class="max-w-xl mt-1 relative sm:mt-0 sm:col-span-2">
                <Button on:clicked={groupSelectMenu.openModal}
                        title={selectedGroups.length ? `${selectedGroups.length} Group${selectedGroups.length > 1 ? 's' : ''} selected` : 'Select group'}
                        color="white"/>
            </div>
        </div>

        <div class="sm:border-t sm:border-gray-200 sm:pt-5">
            <div class="text-gray-800 font-medium">Smart group settings</div>
            <div class="-mt-0.5 text-sm text-gray-500">Show tagged links in the group</div>

            <div class="mt-4 grid gap-3">
                <div>
                    <div class="text-sm text-gray-700 font-medium">any of these</div>

                    {#each group.orTags as tag (tag.id)}
                        <button on:click={() => group.orTags = toggleValueInArray(group.orTags, tag)} type="button"
                                class="inline-flex items-center mr-2 mt-2 py-0.5 px-2.5 bg-primary-100 text-sm text-primary-800 font-medium rounded-full group">
                            <XMark
                                className="mr-1 -ml-1 fill-primary-500 rounded-full group-hover:fill-primary-700 group-hover:bg-primary-200/50"/>
                            {tag.name}
                        </button>
                    {/each}

                    <button on:click={() => handleSelectTagsButtonClick('or', group.orTags)} type="button" class="mt-1 block">
                        <PlusCircle className="text-gray-600"/>
                    </button>
                </div>

                <div>
                    <div class="text-sm text-gray-700 font-medium">all of these</div>

                    {#each group.andTags as tag (tag.id)}
                        <button on:click={() => group.andTags = toggleValueInArray(group.andTags, tag)} type="button"
                                class="inline-flex items-center mr-2 mt-2 py-0.5 px-2.5 bg-primary-100 text-sm text-primary-800 font-medium rounded-full group">
                            <XMark
                                className="mr-1 -ml-1 fill-primary-500 rounded-full group-hover:fill-primary-700 group-hover:bg-primary-200/50"/>
                            {tag.name}
                        </button>
                    {/each}

                    <button on:click={() => handleSelectTagsButtonClick('and', group.andTags)} type="button" class="mt-1 block">
                        <PlusCircle className="text-gray-600"/>
                    </button>
                </div>

                <div>
                    <div class="text-sm text-gray-700 font-medium">none of these</div>

                    {#each group.notTags as tag (tag.id)}
                        <button on:click={() => group.notTags = toggleValueInArray(group.notTags, tag)} type="button"
                                class="inline-flex items-center mr-2 mt-2 py-0.5 px-2.5 bg-primary-100 text-sm text-primary-800 font-medium rounded-full group">
                            <XMark
                                className="mr-1 -ml-1 fill-primary-500 rounded-full group-hover:fill-primary-700 group-hover:bg-primary-200/50"/>
                            {tag.name}
                        </button>
                    {/each}

                    <button on:click={() => handleSelectTagsButtonClick('not', group.notTags)} type="button" class="mt-1 block">
                        <PlusCircle className="text-gray-600"/>
                    </button>
                </div>
            </div>
        </div>
    </Container>

    <svelte:fragment slot="footer">
        <Button on:clicked={() => showModal = false} title="Cancel" color="white"
                class="focus:ring-offset-gray-50"/>
        <Button on:clicked={isEditing ? updateGroup : createGroup}
                title={isEditing ? 'Edit group' : 'Create group'}
                class="focus:ring-offset-gray-50"/>
    </svelte:fragment>
</Modal>

<GroupSelectMenu bind:this={groupSelectMenu} bind:selectedGroups/>
