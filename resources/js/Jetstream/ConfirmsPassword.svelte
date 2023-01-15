<script>
    import {createEventDispatcher} from 'svelte';
    import JetButton from './Button.svelte';
    import JetDialogModal from './DialogModal.svelte';
    import JetInput from './Input.svelte';
    import JetInputError from './InputError.svelte';
    import JetSecondaryButton from './SecondaryButton.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";

    const dispatch = createEventDispatcher();

    export let title = 'Confirm Password';
    export let content = 'For your security, please confirm your password to continue.';
    export let button = 'Confirm';

    let confirmingPassword = false;
    let passwordInput;

    let form = useForm({
        password: '',
        error: '',
    });

    function startConfirmingPassword() {
        axios.get(route('password.confirmation')).then(response => {
            if (response.data.confirmed) {
                dispatch('confirmed');
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
            dispatch('confirmed');
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
    <span on:click={startConfirmingPassword} aria-hidden="true">
        <slot/>
    </span>

    <JetDialogModal show={confirmingPassword} on:close={closeModal}>
        <svelte:fragment slot="title">
            {title}
        </svelte:fragment>

        <svelte:fragment slot="content">
            {content}

            <div class="mt-4">
                <JetInput type="password" class="mt-1 block w-3/4" placeholder="Password"
                          bind:this={passwordInput} bind:value={$form.password}/>

                <JetInputError message={$form.errors.password} class="mt-2"/>
            </div>
        </svelte:fragment>

            <svelte:fragment slot="footer">
            <JetSecondaryButton on:clicked={closeModal}>
                Cancel
            </JetSecondaryButton>

            <JetButton class={['ml-3', $form.processing ? 'opacity-25' : ''].join(' ').trim()}
                       on:clicked={confirmPassword}
                       disabled={$form.processing}>
                {button}
            </JetButton>
            </svelte:fragment>>
    </JetDialogModal>
</span>
