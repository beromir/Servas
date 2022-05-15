<script>
    // Imports
    import Modal from "@/Components/Modals/Modal.svelte";
    import Container from "@/Components/FormLayouts/Modals/Container.svelte";
    import Input from "@/Components/FormLayouts/Modals/Input.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import Badge from '@/Components/Badge.svelte';
    import {useForm} from "@inertiajs/inertia-svelte";
    import {route, clickOutside} from '@/utils';
    import {refreshLinks, refreshTags} from "@/stores";
    import GroupSelectMenu from "@/Partials/GroupSelectMenu.svelte";

    // Stores

    // External props

    // Internal props
    let showModal = false;
    let isEditing = false;
    let linkId = null;
    let showMediaTypeDropdown = false;
    let tags = [];
    let allMediaTypes = [];
    let refreshMediaTypes = true;
    let groupSelectMenu;
    let selectedGroups = [];

    // Reactive statements
    $: selectedMediaType = allMediaTypes.find((e) => {
        if (e.value === null) {
            return null
        }

        return e.value === $linkForm.mediaType;
    });

    $: $linkForm.groups = selectedGroups;

    // Forms
    let linkForm = useForm({
        link: null,
        title: null,
        mediaType: null,
        tags: [],
        groups: [],
    });

    // External functions
    export function prepareCreateNewLink() {
        showMediaTypeDropdown = false;
        getAllTags();
        getMediaTypes();

        if (isEditing) {
            isEditing = false;
            $linkForm.reset();
            linkId = null;
            showModal = true;
            selectedGroups = [];
        } else {
            showModal = true;
        }
    }

    // Internal functions
    function prepareEditLink(e) {
        $linkForm.reset();

        let link = e.detail;

        showMediaTypeDropdown = false;

        $linkForm.link = link.link;
        $linkForm.title = link.title;
        $linkForm.mediaType = link.media_type?.value ?? null;
        selectedGroups = link.groups;

        link.tags.forEach(tag => $linkForm.tags = [...$linkForm.tags, tag.id]);

        linkId = link.id;

        getAllTags();
        getMediaTypes();

        isEditing = true;

        showModal = true;
    }

    function createLink() {
        $linkForm.clearErrors();
        $linkForm.post(route('links.store'), {
            onSuccess: () => {
                $linkForm.reset();
                showModal = false;
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
                showModal = false;
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

    function getMediaTypes() {
        if (refreshMediaTypes) {
            axios.get('/media-types')
                .then(response => {
                    allMediaTypes = response.data;
                });

            refreshMediaTypes = false;
        }
    }

    async function openGroupSelectMenu() {
        groupSelectMenu.enableMultiSelectMode();
        await groupSelectMenu.setSelectedGroups(selectedGroups);
        groupSelectMenu.openModal();
    }
</script>

<svelte:window on:editLink={prepareEditLink} on:prepareCreateNewLink={prepareCreateNewLink}/>

<Modal title={isEditing ? 'Edit link' : 'Create link'} showFooterMenuOnMobile={false} size="xl" bind:showModal>
    <svelte:fragment slot="mobilePrimaryAction">
        <button on:click={isEditing ? editLink : createLink}
                class="text-right text-primary-600 font-medium focus:outline-none sm:hidden" type="button">
            {isEditing ? 'Edit' : 'Create'}
        </button>
    </svelte:fragment>

    <Container>
        <Input label="URL" name="link" type="url" isFirst={true} bind:value={$linkForm.link}
               error={$linkForm.errors.link} autofocus={!isEditing}/>
        <Input label="Title" name="title" placeholder="optional" bind:value={$linkForm.title}
               error={$linkForm.errors.title}/>

        <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
            <span class="block text-sm text-left font-medium text-gray-700 sm:mt-px sm:pt-2">
                Groups
            </span>
            <div class="max-w-xl mt-1 relative sm:mt-0 sm:col-span-2">
                <Button on:clicked={() => openGroupSelectMenu()}
                        title={selectedGroups.length ? `${selectedGroups.length} Group${selectedGroups.length > 1 ? 's' : ''} selected` : 'Select group'}
                        color="white"/>
            </div>
        </div>

        <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                    <span class="block text-sm text-left font-medium text-gray-700 sm:mt-px sm:pt-2">
                        Media type
                    </span>
            <div class="max-w-xl mt-1 relative sm:mt-0 sm:col-span-2">
                <button on:click={() => showMediaTypeDropdown = !showMediaTypeDropdown}
                        type="button"
                        class="bg-white relative w-full border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                        aria-haspopup="listbox" aria-expanded="true" aria-labelledby="media-type-label">
                    <span class="block truncate" id="media-type-dropdown-label">
                        {selectedMediaType?.label ?? 'Select media type'}
                    </span>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <!-- Heroicon name: solid/selector -->
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 20 20" fill="currentColor"
                             aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </span>
                </button>

                {#if $linkForm.errors.mediaType}
                    <div class="text-yellow-800">{$linkForm.errors.mediaType}</div>
                {/if}

                {#if showMediaTypeDropdown}
                    <ul use:clickOutside on:click_outside={() => showMediaTypeDropdown = false}
                        class="absolute bottom-12 z-10 mt-1 w-full bg-white shadow-lg max-h-32 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                        tabindex="-1" role="listbox" aria-labelledby="listbox-label"
                        aria-activedescendant="listbox-option-3">
                        <!--
                          Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation.

                          Highlighted: "text-white bg-indigo-600", Not Highlighted: "text-gray-900"
                        -->

                        <li on:click|preventDefault={() => {$linkForm.mediaType = null; showMediaTypeDropdown = false}}
                            class="text-gray-900 cursor-default select-none relative py-2 pl-3 pr-9" role="option">
                            <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                            <span class="font-normal block truncate">
                                No specific media type
                            </span>

                            <!--
                              Checkmark, only display for selected option.

                              Highlighted: "text-white", Not Highlighted: "text-indigo-600"
                            -->
                            <span class:hidden={$linkForm.mediaType !== null}
                                  class="text-indigo-600 absolute inset-y-0 right-0 flex items-center pr-4">
                                <!-- Heroicon name: solid/check -->
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                     fill="currentColor"
                                     aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </span>
                        </li>

                        {#each allMediaTypes as mediaType (mediaType.value)}
                            <li on:click|preventDefault={() => {$linkForm.mediaType = mediaType.value; showMediaTypeDropdown = false}}
                                class="text-gray-900 cursor-default select-none relative py-2 pl-3 pr-9" role="option">
                                <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                                <span class="font-normal block truncate">
                                    {mediaType.label}
                                </span>

                                <!--
                                  Checkmark, only display for selected option.

                                  Highlighted: "text-white", Not Highlighted: "text-indigo-600"
                                -->
                                <span class:hidden={mediaType.value !== $linkForm.mediaType}
                                      class="text-indigo-600 absolute inset-y-0 right-0 flex items-center pr-4">
                                    <!-- Heroicon name: solid/check -->
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                         fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </span>
                            </li>
                        {/each}
                    </ul>
                {/if}
            </div>
        </div>

        <!-- Tags -->
        <div class="space-y-3 sm:border-t sm:border-gray-200 sm:pt-2">
            {#each tags as tag (tag.id)}
                <Badge
                    on:clicked={() => toggleValueInArray(tag.id)}
                    title={tag.name} large={false}
                    color={$linkForm.tags.includes(tag.id) ? '' : 'gray'}
                    class="mr-1.5 first:mt-3 last:mr-0"/>
            {/each}
        </div>
    </Container>

    <svelte:fragment slot="footer">
        <Button on:clicked={isEditing ? editLink : createLink}
                title={isEditing ? 'Edit link' : 'Create link'}
                class="focus:ring-offset-gray-50 sm:ml-3"/>
        <Button on:clicked={() => showModal = false} title="Cancel" color="white"
                class="mt-3 focus:ring-offset-gray-50 sm:mt-0"/>
    </svelte:fragment>
</Modal>

<GroupSelectMenu bind:this={groupSelectMenu} bind:selectedGroups/>
