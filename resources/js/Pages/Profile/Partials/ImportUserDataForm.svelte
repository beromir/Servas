<script>
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route, toggleValueInArray} from "@/utils";
    import FileUpload from "@/Components/FormLayouts/Inputs/FileUpload.svelte";
    import SuccessMessage from "@/Jetstream/SuccessMessage.svelte";
    import RadioGroup from "@/Components/FormLayouts/Inputs/RadioGroup.svelte";
    import Radio from "@/Components/FormLayouts/Inputs/Radio.svelte";
    import Checkbox from "@/Components/Checkbox.svelte";

    let importOptions = [
        'links',
        'groups',
        'tags',
    ];

    let fileInput = $state();

    let userDataform = useForm({
        importSource: 'json',
        importOptions: [],
        importFile: null,
    });

    let showSuccessMessage = $state(false);

    function importUserData() {
        showSuccessMessage = false;

        $userDataform.post(route('import'), {
            preserveScroll: true,
            onSuccess: () => {
                showSuccessMessage = true;
                $userDataform.reset();
                fileInput.clearInput();
            }
        });
    }
</script>

<JetFormSection submitted={importUserData}>
    {#snippet title()}
        Import Data
    {/snippet}

    {#snippet description()}
        Import data from a JSON file.
    {/snippet}

    {#snippet form()}
        <div class="col-span-full space-y-6">
            <RadioGroup label="Import source">
                <Radio bind:currentValue={$userDataform.importSource} id="import-json" name="exportFormat" value="json"
                       label="JSON (Servas export)"/>
                <Radio bind:currentValue={$userDataform.importSource} id="import-html" name="exportFormat" value="html"
                       label="HTML (Browser bookmarks)"/>
            </RadioGroup>

            <div>
                <FileUpload input={event => $userDataform.importFile = event.file} bind:this={fileInput}
                            label={`Select export file (${$userDataform.importSource === 'json' ? '.json' : '.html'})`}
                            accept={$userDataform.importSource === 'json' ? '.json' : '.html'}/>
            </div>

            <div>
                {#each importOptions as option}
                    <label class="flex items-center">
                        <Checkbox
                            onchange={() => $userDataform.importOptions = toggleValueInArray($userDataform.importOptions, option)}/>

                        <span class="ml-2 text-sm text-gray-600 capitalize dark:text-gray-300">{option}</span>
                    </label>
                {/each}
            </div>
        </div>
    {/snippet}

    {#snippet actions()}
        <SuccessMessage bind:show={showSuccessMessage} message="The data was imported."/>
        <JetButton type="submit" disabled={!$userDataform.importOptions.length || $userDataform.importFile === null}>
            Import
        </JetButton>
    {/snippet}
</JetFormSection>
