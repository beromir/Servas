<script>
    import JetSectionTitle from '@/Jetstream/SectionTitle.svelte'

    let { title, description, content } = $props();

    const title_render = $derived(title);
    const description_render = $derived(description);
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
        <div class="px-4 py-5 sm:p-6 bg-white shadow-sm ring-contrast sm:rounded-lg dark:bg-gray-900">
            {@render content?.()}
        </div>
    </div>
</div>
