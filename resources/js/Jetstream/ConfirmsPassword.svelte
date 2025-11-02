<script>
    import JetButton from './Button.svelte';
    import JetDialogModal from './DialogModal.svelte';
    import JetInput from './Input.svelte';
    import JetInputError from './InputError.svelte';
    import JetSecondaryButton from './SecondaryButton.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";

    let {
        title = 'Confirm Password',
        content = 'For your security, please confirm your password to continue.',
        button = 'Confirm',
        confirmed,
        children
    } = $props();

    let confirmingPassword = $state(false);
    let passwordInput = $state();

    let form = useForm({
        password: '',
        error: '',
    });

    function startConfirmingPassword() {
        axios.get(route('password.confirmation')).then(response => {
            if (response.data.confirmed) {
                confirmed();
            } else {
                confirmingPassword = true;

                setTimeout(() => passwordInput.focus(), 250)
            }
        })
    }

    function confirmPassword() {
        $form.processing = true;

        axios.post(route('password.confirm'), {
            password: $form.password,
        }).then(() => {
            $form.processing = false;
            closeModal()
            // this.$nextTick(() => this.$emit('confirmed'));
            confirmed();
        }).catch(error => {
            $form.processing = false;
            $form.setError('password', error.response.data.errors.password[0]);
            passwordInput.focus()
        });
    }

    function closeModal() {
        confirmingPassword = false
        $form.reset();
    }
</script>

<span>
    <span aria-hidden="true" onclick={startConfirmingPassword}>
        {@render children?.()}
    </span>

    <JetDialogModal close={closeModal} show={confirmingPassword}>
        {#snippet title()}
                {title}
        {/snippet}

        {#snippet content()}
                {content}

            <div class="mt-4">
                    <JetInput type="password" class="mt-1 block w-3/4" placeholder="Password"
                              bind:this={passwordInput} bind:value={$form.password}/>

                    <JetInputError message={$form.errors.password} class="mt-2"/>
                </div>
        {/snippet}

        {#snippet footer()}
                <JetSecondaryButton clicked={closeModal}>
                    Cancel
                </JetSecondaryButton>

                <JetButton class={['ml-3', $form.processing ? 'opacity-25' : ''].join(' ').trim()}
                           clicked={confirmPassword} disabled={$form.processing}>
                    {button}
                </JetButton>
        {/snippet}>
    </JetDialogModal>
</span>
