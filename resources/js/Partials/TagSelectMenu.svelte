<script>
    import Button from "@/Components/Buttons/Button.svelte";
    import Modal from '@/Components/Modals/Modal.svelte';
    import {isUndefined} from "lodash";
    import {createEventDispatcher} from 'svelte';

    export let selectedTags = [];        // property to access from outside; contains tag IDs; will only change when clicking "OK"

    let tags = [];
    let internalSelectedTags = [];       // property for internal use only; contains tag objects
    let showModal = false;

    const dispatch = createEventDispatcher();

    export async function openModal() {
        await getAllTags();

        showModal = true;
    }

    function saveChanges() {
        selectedTags = [];

        internalSelectedTags.forEach((tag) => {
            selectedTags = [...selectedTags, tag.id];
        });

        showModal = false;

        dispatch('changesSaved');
    }

    async function getAllTags() {
        await axios.get('/all-tags')
            .then(response => {
                tags = response.data
            });
    }

    function selectTag(tag) {
        let index = getIndexOfTagId(tag.id, internalSelectedTags);

        if (index !== -1) {
            internalSelectedTags.splice(index, 1);
            internalSelectedTags = internalSelectedTags;
        } else {
            internalSelectedTags = [...internalSelectedTags, tag];
        }
    }

    export function reset() {
        selectedTags = [];
        internalSelectedTags = [];
    }

    export async function setSelectedTags(tagsToSet) {
        internalSelectedTags = [];
        selectedTags = [];

        if (!tagsToSet.length) {
            return;
        }

        await getAllTags();

        tagsToSet.forEach((tagId) => {
            let tag = tags.find(({id}) => {
                return id === tagId;
            })

            if (!isUndefined(tag)) {
                internalSelectedTags = [...internalSelectedTags, tags];
            }
        });

        selectedTags = tagsToSet;
    }

    function getIndexOfTagId(tagId, arrayToSearch) {
        return arrayToSearch.findIndex(tag => tag.id === tagId);
    }
</script>

<Modal title="Select tags" bind:showModal>
    <div class="mt-5">
        <ul class="mt-3 divide-y divide-gray-100">
            {#each tags as tag (tag.id)}
                <li class="flex justify-between items-center text-gray-900 cursor-default select-none relative py-2.5 pl-8 pr-4">
                    <div class="font-normal block truncate">{tag.name}</div>

                    {#if getIndexOfTagId(tag.id, internalSelectedTags) !== -1}
                        <div class="text-indigo-600 absolute inset-y-0 left-0 flex items-center pl-1.5">
                            <!-- Heroicon name: solid/check -->
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 fill="currentColor"
                                 aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                    {/if}
                    <button on:click|stopPropagation={() => selectTag(tag)} type="button"
                            class="text-sm text-gray-700">
                        {getIndexOfTagId(tag.id, internalSelectedTags) !== -1 ? 'Unselect' : 'Select'}
                    </button>
                </li>

            {:else}
                <li class="py-2.5 px-4 text-gray-600 text-center">No tags found</li>
            {/each}
        </ul>
    </div>

    <svelte:fragment slot="footer">
        <Button on:clicked={saveChanges} title="OK"
                class="focus:ring-offset-gray-50 sm:ml-3"/>
        <Button on:clicked={() => showModal = false} title="Cancel" color="white"
                class="hidden mt-3 focus:ring-offset-gray-50 sm:inline-flex sm:mt-0"/>
    </svelte:fragment>
</Modal>
