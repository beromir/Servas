<script>
    import JetSectionTitle from '@/Jetstream/SectionTitle.svelte';

    let {
        actions,
        title,
        description,
        form,
        submitted,
        ...rest
    } = $props();

    let formSection = $state();

    let hasActions = $derived(actions);

    export function submit() {
        formSection.submit();
    }

    const title_render = $derived(title);
    const description_render = $derived(description);

    function handleSubmit(event) {
        event.preventDefault();

        submitted();
    }
</script>

<div class="md:grid md:grid-cols-3 md:gap-6">
    <JetSectionTitle>
        {#snippet title()}
            {@render title_render?.()}
        {/snippet}
        {#snippet description()}
            {@render description_render?.()}
        {/snippet}
    </JetSectionTitle>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <form onsubmit={handleSubmit} bind:this={formSection} {...rest}>
            <div class={['px-4 py-5 bg-white sm:p-6 shadow-sm ring-contrast dark:bg-gray-900',
                 hasActions ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md'].join(' ').trim()}>
                <div class="grid grid-cols-6 gap-6">
                    {@render form?.()}
                </div>
            </div>

            {#if hasActions}
                <div
                    class="flex items-center justify-end px-4 py-3 bg-gray-100 border-t border-gray-200 text-right sm:px-6 shadow-sm ring-contrast sm:rounded-bl-md sm:rounded-br-md dark:bg-gray-950 dark:border-gray-800">
                    {@render actions?.()}
                </div>
            {/if}
        </form>
    </div>
</div>
