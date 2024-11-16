<script>
    import {createEventDispatcher} from "svelte";

    const dispatch = createEventDispatcher();

    export let label = '';

    let fileInput;

    export function clearInput() {
        fileInput.value = null;
    }
</script>

<label>
    <span class="block font-medium text-sm text-gray-700 dark:text-gray-400">{label}</span>
    <input type="file" on:input={e => dispatch('input', { file: e.target.files[0] })} bind:this={fileInput}
           {...$$restProps}
           class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-900 hover:file:bg-gray-200 hover:file:cursor-pointer focus:outline-none dark:text-gray-400 dark:file:bg-gray-800 dark:file:text-gray-100 dark:file:border-gray-600 dark:hover:file:bg-gray-700"/>
</label>
