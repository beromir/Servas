<script>
    import Modal from "@/Components/Modals/Modal.svelte";
    import Container from "@/Components/FormLayouts/Modals/Container.svelte";
    import Input from "@/Components/FormLayouts/Modals/Input.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import Badge from '@/Components/Badge.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route} from '@/utils';
    import {refreshLinks, refreshTags} from "@/stores";
    import GroupSelectMenu from "@/Partials/GroupSelectMenu.svelte";

    let isEditing = $state(false);
    let linkId = null;
    let tags = $state([]);
    let groupSelectMenu = $state();
    let selectedGroups = $state([]);
    let modal;

    let linkForm = useForm({
        link: null,
        title: null,
        tags: [],
        groups: [],
    });

    $effect(() => {
        $linkForm.groups = selectedGroups;
    });

    // External functions
    export function prepareCreateNewLink() {
        getAllTags();

        if (isEditing) {
            isEditing = false;
            $linkForm.reset();
            groupSelectMenu.reset();
            linkId = null;
            modal.show();
            selectedGroups = [];
        } else {
            modal.show();
        }
    }

    // Internal functions
    function prepareEditLink(e) {
        $linkForm.reset();

        let link = e.detail;

        $linkForm.link = link.link;
        $linkForm.title = link.title;
        selectedGroups = link.groups;

        link.tags.forEach(tag => $linkForm.tags = [...$linkForm.tags, tag.id]);

        linkId = link.id;

        getAllTags();

        isEditing = true;

        modal.show();
    }

    function createLink() {
        $linkForm.clearErrors();
        $linkForm.post(route('links.store'), {
            onSuccess: () => {
                $linkForm.reset();
                groupSelectMenu.reset();
                modal.close();
                $refreshLinks = true;
            },
        });
    }

    function editLink() {
        $linkForm.clearErrors();
        $linkForm.patch(route('links.update', linkId), {
            preserveScroll: true,
            onSuccess: () => {
                linkId = null;
                $linkForm.reset();
                groupSelectMenu.reset();
                modal.show();
                $refreshLinks = true;
            },
        });
    }

    function toggleValueInArray(tagId) {
        let index = $linkForm.tags.indexOf(tagId);
        if (index !== -1) {
            $linkForm.tags.splice(index, 1);
            $linkForm.tags = $linkForm.tags;
        } else {
            $linkForm.tags = [...$linkForm.tags, tagId];
        }
    }

    function getAllTags() {
        if ($refreshTags) {
            axios.get('/all-tags')
                .then(response => {
                    tags = response.data;
                });

            $refreshTags = false;
        }
    }

    async function openGroupSelectMenu() {
        groupSelectMenu.enableMultiSelectMode();
        await groupSelectMenu.setSelectedGroups(selectedGroups);
        groupSelectMenu.openModal();
    }
</script>

<svelte:window oneditLink={prepareEditLink} onprepareCreateNewLink={prepareCreateNewLink}/>

<Modal bind:this={modal} title={isEditing ? 'Edit link' : 'Create link'} showFooterMenuOnMobile={false} size="xl">
    {#snippet mobilePrimaryAction()}
        <button onclick={isEditing ? editLink : createLink}
                class="text-right text-primary-600 font-medium sm:hidden dark:text-gray-100"
                type="button">
            {isEditing ? 'Edit' : 'Create'}
        </button>
    {/snippet}

    <Container>
        <Input label="URL" name="link" type="url" isFirst={true} bind:value={$linkForm.link}
               error={$linkForm.errors.link} autofocus={!isEditing}/>
        <Input label="Title" name="title" placeholder="optional" bind:value={$linkForm.title}
               error={$linkForm.errors.title}/>

        <div
            class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5 dark:border-gray-700">
            <span class="block text-sm text-left font-medium text-gray-700 sm:mt-px sm:pt-2 dark:text-gray-200">
                Groups
            </span>
            <div class="max-w-xl mt-1 relative sm:mt-0 sm:col-span-2">
                <Button clicked={() => openGroupSelectMenu()}
                        title={selectedGroups.length ? `${selectedGroups.length} Group${selectedGroups.length > 1 ? 's' : ''} selected` : 'Select group'}
                        color="white"/>
            </div>
        </div>

        <!-- Tags -->
        <div class="space-y-3 sm:border-t sm:border-gray-200 sm:pt-2 dark:border-gray-700">
            {#each tags as tag (tag.id)}
                <Badge
                    clicked={() => toggleValueInArray(tag.id)}
                    title={tag.name} large={false}
                    color={$linkForm.tags.includes(tag.id) ? '' : 'gray'}
                    class="mr-1.5 first:mt-3 last:mr-0"/>
            {/each}
        </div>
    </Container>

    {#snippet footer()}
        <Button clicked={() => modal.close()} title="Cancel" color="white"/>
        <Button clicked={isEditing ? editLink : createLink}
                title={isEditing ? 'Edit link' : 'Create link'}/>
    {/snippet}
</Modal>

<GroupSelectMenu bind:this={groupSelectMenu} bind:selectedGroups/>
