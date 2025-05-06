<script>
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import {route} from "@/utils";
    import {useForm} from "@inertiajs/svelte";

    let exportOptions = [
        'links',
        'groups',
        'tags',
    ];

    let form = useForm({
        exportFormat: 'json',
        exportOptions: [],
    });

    function exportUserData() {
        $form.post(route('export'), {
            preserveScroll: true,
            onSuccess: () => {
                $form.reset();
            }
        });
    }
</script>

<JetFormSection on:submitted={exportUserData}>
    <svelte:fragment slot="title">
        Export Data
    </svelte:fragment>

    <svelte:fragment slot="description">
        Export your account data as JSON or HTML.
    </svelte:fragment>

    <svelte:fragment slot="form">
        <div class="col-span-3 space-y-6">
            <fieldset class="dark:text-white">
                <legend>Export format</legend>

                <div>
                    <input type="radio" bind:group={$form.exportFormat} id="export-json" name="export-format"
                           value="json"/>
                    <label for="export-json">JSON (Servas export)</label>
                </div>

                <div>
                    <input type="radio" bind:group={$form.exportFormat} id="export-html" name="export-format"
                           value="html"/>
                    <label for="export-html">HTML (Browser bookmarks)</label>
                </div>
            </fieldset>

            <div>
                {#each exportOptions as option}
                    <label class="flex items-center">
                        <input type="checkbox" name="exportOptions[]" value={option} bind:group={$form.exportOptions}
                               class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:ring-offset-gray-800"/>
                        <span class="ml-2 text-sm text-gray-600 capitalize dark:text-gray-300">{option}</span>
                    </label>
                {/each}
            </div>
        </div>
    </svelte:fragment>

    <svelte:fragment slot="actions">
        <JetButton type="submit" disabled={!$form.exportOptions.length}>
            Export
        </JetButton>
    </svelte:fragment>
</JetFormSection>
