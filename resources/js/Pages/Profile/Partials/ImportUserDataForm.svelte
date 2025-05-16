<script>
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";
    import FileUpload from "@/Components/FormLayouts/Inputs/FileUpload.svelte";
    import SuccessMessage from "@/Jetstream/SuccessMessage.svelte";
    import RadioGroup from "@/Components/FormLayouts/Inputs/RadioGroup.svelte";
    import Radio from "@/Components/FormLayouts/Inputs/Radio.svelte";

    let importOptions = [
        'links',
        'groups',
        'tags',
    ];

    let fileInput;

    let form = useForm({
        importSource: 'json',
        importOptions: [],
        importFile: null,
    });

    let showSuccessMessage = false;

    function importUserData() {
        showSuccessMessage = false;

        $form.post(route('import'), {
            preserveScroll: true,
            onSuccess: () => {
                showSuccessMessage = true;
                $form.reset();
                fileInput.clearInput();
            }
        });
    }
</script>

<JetFormSection on:submitted={importUserData}>
    <svelte:fragment slot="title">
        Import Data
    </svelte:fragment>

    <svelte:fragment slot="description">
        Import data from a JSON file.
    </svelte:fragment>

    <svelte:fragment slot="form">
        <div class="col-span-full space-y-6">
            <RadioGroup label="Import source">
                <Radio bind:currentValue={$form.importSource} id="import-json" name="exportFormat" value="json"
                       label="JSON (Servas export)"/>
                <Radio bind:currentValue={$form.importSource} id="import-html" name="exportFormat" value="html"
                       label="HTML (Browser bookmarks)"/>
            </RadioGroup>

            <div>
                <FileUpload on:input={event => $form.importFile = event.detail.file} bind:this={fileInput}
                            label={`Select export file (${$form.importSource === 'json' ? '.json' : '.html'})`}
                            accept={$form.importSource === 'json' ? '.json' : '.html'}/>
            </div>

            <div>
                {#each importOptions as option}
                    <label class="flex items-center">
                        <input type="checkbox" value={option} bind:group={$form.importOptions}
                               class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:ring-offset-gray-800"/>
                        <span class="ml-2 text-sm text-gray-600 capitalize dark:text-gray-300">{option}</span>
                    </label>
                {/each}
            </div>
        </div>
    </svelte:fragment>

    <svelte:fragment slot="actions">
        <SuccessMessage bind:show={showSuccessMessage} message="The data was imported."/>
        <JetButton type="submit" disabled={!$form.importOptions.length || $form.importFile === null}>
            Import
        </JetButton>
    </svelte:fragment>
</JetFormSection>
