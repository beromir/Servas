<script>
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";

    let importOptions = [
        'links',
        'groups',
        'tags',
    ];

    let form = useForm({
        importOptions: [],
        importFile: null,
    })

    function importUserData() {
        $form.post(route('import'), {
            preserveScroll: true,
            onSuccess: () => $form.reset(),
        });
    }
</script>

<JetFormSection on:submitted={importUserData}>
    <svelte:fragment slot="title">
        Import User Data
    </svelte:fragment>

    <svelte:fragment slot="description">
        Import your data from a JSON file.
    </svelte:fragment>

    <svelte:fragment slot="form">
        <div class="col-span-3 space-y-6">
            <div>
                <label>
                    <span class="block font-medium text-sm text-gray-700 ">Select export file (.json)</span>
                    <input type="file" on:input={e => $form.importFile = e.target.files[0]}
                           class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-900 hover:file:bg-gray-200 hover:file:cursor-pointer focus:outline-none"/>
                </label>
            </div>

            <div>
                {#each importOptions as option}
                    <label class="flex items-center">
                        <input type="checkbox" value={option} bind:group={$form.importOptions}
                               class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"/>
                        <span class="ml-2 text-sm text-gray-600 capitalize">{option}</span>
                    </label>
                {/each}
            </div>
        </div>
    </svelte:fragment>

    <svelte:fragment slot="actions">
        <JetButton type="submit" disabled={!$form.importOptions.length || $form.importFile === null}>
            Import
        </JetButton>
    </svelte:fragment>
</JetFormSection>
