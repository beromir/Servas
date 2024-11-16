<script>
    import {createEventDispatcher} from 'svelte';
    import JetSectionTitle from '@/Jetstream/SectionTitle.svelte';

    const dispatch = createEventDispatcher();

    let form;

    $: hasActions = $$slots.actions;

    export function submit() {
        form.submit();
    }
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
        <form on:submit|preventDefault={() => dispatch('submitted')} bind:this={form} {...$$restProps}>
            <div class={['px-4 py-5 bg-white sm:p-6 shadow ring-contrast dark:bg-gray-900',
                 hasActions ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md'].join(' ').trim()}>
                <div class="grid grid-cols-6 gap-6">
                    <slot name="form"/>
                </div>
            </div>

            {#if hasActions}
                <div
                    class="flex items-center justify-end px-4 py-3 bg-gray-100 border-t border-gray-200 text-right sm:px-6 shadow ring-contrast sm:rounded-bl-md sm:rounded-br-md dark:bg-gray-950 dark:border-gray-800">
                    <slot name="actions"/>
                </div>
            {/if}
        </form>
    </div>
</div>
