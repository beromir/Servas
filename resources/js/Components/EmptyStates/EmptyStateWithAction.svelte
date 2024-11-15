<script>
    import {createEventDispatcher} from 'svelte';
    import clsx from "clsx";

    export let title = '';

    const dispatch = createEventDispatcher();
</script>

<div {...$$restProps} class={$$restProps.class}>
    <button on:click="{() => dispatch('clicked')}" type="button"
            class="block w-full border-2 border-gray-400 border-dashed rounded-lg p-12 text-center hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 focus:ring-offset-gray-100 dark:border-gray-500 dark:hover:border-gray-400 dark:focus:ring-offset-gray-900">
        {#if $$slots.icon}
            <span class="text-gray-500 [&>svg]:mx-auto [&>svg]:size-12 dark:text-gray-300">
                <slot name="icon"/>
            </span>
        {/if}

        <span class="mt-3 text-sm font-medium text-gray-900 dark:text-gray-100">
            {title}
        </span>
    </button>
</div>
