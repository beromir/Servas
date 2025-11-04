<script>
    let { title = '', icon, clicked, ...rest } = $props();
</script>

<div {...rest} class={rest.class}>
    <button onclick={() => clicked()} type="button"
            class="block w-full border-2 border-gray-400 border-dashed rounded-lg p-12 text-center hover:border-gray-500 dark:border-gray-500 dark:hover:border-gray-400">
        {#if icon}
            <span class="text-gray-500 [&>svg]:mx-auto [&>svg]:size-12 dark:text-gray-300">
                {@render icon?.()}
            </span>
        {/if}

        <span class="mt-3 text-sm font-medium text-gray-900 dark:text-gray-100">
            {title}
        </span>
    </button>
</div>
