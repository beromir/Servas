<script>
    import Modal from "@/Components/Modals/Modal.svelte";
    import Container from "@/Components/FormLayouts/Modals/Container.svelte";
    import Input from "@/Components/FormLayouts/Modals/Input.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import {useForm} from "@inertiajs/svelte";
    import {route} from '@/utils';
    import {refreshGroups, refreshTags} from "@/stores";
    import GroupSelectMenu from "@/Partials/GroupSelectMenu.svelte";
    import Badge from "@/Components/Badge.svelte";

    let showModal = false;
    let isEditing = false;
    let group = null;
    let groupSelectMenu;
    let selectedGroups = [];
    let tags = [];

    let form = useForm({
        title: null,
        parentGroupId: null,
        tags: [],
    });

    $: $form.parentGroupId = selectedGroups[0];

    export function showCreationView(parentGroupId = null) {
        getAllTags();

        if (parentGroupId !== null) {
            groupSelectMenu.setSelectedGroups([parentGroupId]);
        } else {
            groupSelectMenu.reset();
        }

        isEditing && reset();

        showModal = true;
    }

    function showEditingView(groupToEdit) {
        getAllTags();

        reset();

        group = groupToEdit;

        $form.title = group.title;
        groupSelectMenu.setSelectedGroups([group.parentGroupId]);

        isEditing = true;

        showModal = true;
    }

    function createGroup() {
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

    function toggleValueInArray(tagId) {
        let index = $form.tags.indexOf(tagId);
        if (index !== -1) {
            $form.tags.splice(index, 1);
            $form.tags = $form.tags;
        } else {
            $form.tags = [...$form.tags, tagId];
        }
    }

    function getAllTags() {
        axios.get('/all-tags')
            .then(response => {
                tags = response.data;
            });
    }

    function reset() {
        $form.reset();
        groupSelectMenu.reset();
        isEditing = false;
        group = null;
        selectedGroups = [];
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

        <!-- Tags -->
        <div class="space-y-3 sm:border-t sm:border-gray-200 sm:pt-2">
            {#each tags as tag (tag.id)}
                <Badge
                    on:clicked={() => toggleValueInArray(tag.id)}
                    title={tag.name} large={false}
                    color={$form.tags.includes(tag.id) ? '' : 'gray'}
                    class="mr-1.5 first:mt-3 last:mr-0"/>
            {/each}
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
