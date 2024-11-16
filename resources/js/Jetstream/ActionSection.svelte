<script>
    import JetSectionTitle from '@/Jetstream/SectionTitle.svelte'
</script>

<div class="md:grid md:grid-cols-3 md:gap-6">
    <JetSectionTitle>
        <svelte:fragment slot="title">
            <slot name="title"/>
        </svelte:fragment>
        <svelte:fragment slot="description">
            <slot name="description"/>
        </svelte:fragment>
    </JetSectionTitle>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <div class="px-4 py-5 sm:p-6 bg-white shadow ring-contrast sm:rounded-lg dark:bg-gray-900">
            <slot name="content"/>
        </div>
    </div>
</div>
