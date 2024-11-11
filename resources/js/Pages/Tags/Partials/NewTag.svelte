<script>
    import {createEventDispatcher} from 'svelte';
    import Button from "@/Components/Buttons/Button.svelte";
    import Input from '@/Components/Inputs/Input.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route, dispatchCustomEvent} from "@/utils";
    import {refreshTags} from "@/stores";

    const dispatch = createEventDispatcher();

    export let modeIsEdit = false;
    let tag = {};
    let input;

    let form = useForm({
        tagName: null,
    });

    function createTag() {
        $form.clearErrors();
        $form.post(route('tags.store'), {
            preserveScroll: true,
            onSuccess: () => {
                $form.reset();
                $refreshTags = true;
            },
        });
    }

    function editTag() {
        $form.clearErrors();
        $form.patch(route('tags.update', tag.id), {
            preserveScroll: true,
            onSuccess: () => {
                endEditMode();
                $refreshTags = true;
            },
        });
    }

    function deleteTag() {
        dispatchCustomEvent('deleteTag', tag);
    }

    function endEditMode() {
        tag = {};
        $form.clearErrors();
        $form.reset();
        modeIsEdit = false;
    }

    export function initEditMode(tagToEdit) {
        $form.clearErrors();
        tag = tagToEdit;
        $form.tagName = tagToEdit.name;
        modeIsEdit = true;
    }

    export const focus = () => input.focus();
</script>

<svelte:window on:tagDeletionWasSuccessful={endEditMode}/>

<form on:submit|preventDefault={modeIsEdit ? editTag : createTag} class="space-y-3 sm:flex sm:space-x-3 sm:space-y-0">
    <div class="w-full sm:max-w-xs">
        <Input placeholder="Enter tag name..." bind:value={$form.tagName} bind:this={input}/>
    </div>
    <Button title={modeIsEdit ? 'Save changes' : 'Create tag'} type="submit"
            class="justify-center w-full whitespace-nowrap focus:ring-offset-gray-100 sm:w-auto"/>


    {#if modeIsEdit}
        <div class="flex items-center">
            <Button
                title="Delete tag"
                on:clicked={deleteTag}
                color="white"
                class="whitespace-nowrap focus:ring-offset-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                          clip-rule="evenodd"/>
                </svg>
            </Button>

            <button on:click={endEditMode} type="button"
                    class="w-full text-gray-700 text-sm focus:outline-none focus:ring-primary-400 focus:ring-offset-gray-100 focus:ring-2 focus:ring-offset-2 sm:ml-3 dark:text-gray-200">
                Cancel
            </button>
        </div>
    {/if}

    {#if $form.errors.tagName}
        <div class="inline-flex ml-4 items-center text-yellow-800 whitespace-nowrap">{$form.errors.tagName}</div>
    {/if}
</form>
