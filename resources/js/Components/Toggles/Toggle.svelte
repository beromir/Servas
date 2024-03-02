<script>
    import {createEventDispatcher} from "svelte";

    export let title = '';
    export let toggled = false;

    const dispatch = createEventDispatcher();

    function toggle() {
        toggled = !toggled;

        dispatch('toggled', toggled);
    }
</script>

<div class="flex items-center gap-x-2 text-sm text-gray-700 font-medium">
    <button on:click={toggle} type="button"
            class="relative inline-flex h-5 w-8 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
            class:bg-gray-300={!toggled} class:bg-primary-500={toggled} role="switch" aria-checked="false">
        <span class="sr-only">{title}</span>
        <span aria-hidden="true"
              class="translate-x-0 pointer-events-none inline-block size-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
              class:translate-x-3={toggled}></span>
    </button>

    <span on:click={toggle} on:keydown={toggle} role="button" tabindex="-1">
        {title}
    </span>
</div>
