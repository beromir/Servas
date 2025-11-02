<script>
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import JetInput from '@/Jetstream/Input.svelte';
    import JetInputError from '@/Jetstream/InputError.svelte';
    import JetLabel from '@/Jetstream/Label.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";

    let passwordInput = $state();
    let currentPasswordInput = $state();

    const passwordForm = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    function updatePassword() {
        $passwordForm.put(route('user-password.update'), {
            errorBag: 'updatePassword',
            preserveScroll: true,
            onSuccess: () => $passwordForm.reset(),
            onError: () => {
                if ($passwordForm.errors.password) {
                    $passwordForm.reset('password', 'password_confirmation');
                    passwordInput.focus();
                }

                if ($passwordForm.errors.current_password) {
                    $passwordForm.reset('current_password');
                    currentPasswordInput.focus();
                }
            }
        })
    }
</script>

<JetFormSection submitted={updatePassword}>
    {#snippet title()}
        Update Password
    {/snippet}

    {#snippet description()}
        Ensure your account is using a long, random password to stay secure.
    {/snippet}

    {#snippet form()}
        <div class="col-span-6 sm:col-span-4">
            <JetLabel id="current_password" label="Current Password"/>
            <JetInput id="current_password" type="password" class="mt-1 block w-full"
                      bind:value={$passwordForm.current_password}
                      bind:this={currentPasswordInput} autocomplete="current-password"/>
            <JetInputError message={$passwordForm.errors.current_password} class="mt-2"/>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <JetLabel id="password" label="New Password"/>
            <JetInput id="password" type="password" class="mt-1 block w-full" bind:value={$passwordForm.password}
                      bind:this={passwordInput}
                      autocomplete="new-password"/>
            <JetInputError message={$passwordForm.errors.password} class="mt-2"/>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <JetLabel id="password_confirmation" label="Confirm Password"/>
            <JetInput id="password_confirmation" type="password" class="mt-1 block w-full"
                      bind:value={$passwordForm.password_confirmation} autocomplete="new-password"/>
            <JetInputError message={$passwordForm.errors.password_confirmation} class="mt-2"/>
        </div>
    {/snippet}

    {#snippet actions()}
        <JetButton type="submit" class={$passwordForm.processing ? 'opacity-25' : ''}
                   disabled={$passwordForm.processing}>
            Save
        </JetButton>
    {/snippet}
</JetFormSection>
