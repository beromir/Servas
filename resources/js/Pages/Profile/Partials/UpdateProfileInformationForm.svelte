<script>
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import JetInput from '@/Jetstream/Input.svelte';
    import JetInputError from '@/Jetstream/InputError.svelte';
    import JetLabel from '@/Jetstream/Label.svelte';
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.svelte';
    import {page, useForm, router} from "@inertiajs/svelte";
    import {route} from "@/utils";
    // import JetActionMessage from '@/Jetstream/ActionMessage.vue';

    export let user = {};

    let form = useForm({
        _method: 'PUT',
        name: user.name,
        email: user.email,
        photo: null,
    });

    let photoPreview = null;
    let photoElement;

    function updateProfileInformation() {
        if (photoElement) {
            $form.photo = photoElement.files[0]
        }

        $form.post(route('user-profile-information.update'), {
            errorBag: 'updateProfileInformation',
            preserveScroll: true,
            onSuccess: () => clearPhotoFileInput(),
        });
    }

    function selectNewPhoto() {
        photoElement.click();
    }

    function updatePhotoPreview() {
        const photo = photoElement.files[0];

        if (!photo) return;

        const reader = new FileReader();

        reader.onload = (e) => {
            photoPreview = e.target.result;
        };

        reader.readAsDataURL(photo);
    }

    function deletePhoto() {
        router.delete(route('current-user-photo.destroy'), {
            preserveScroll: true,
            onSuccess: () => {
                photoPreview = null;
                clearPhotoFileInput();
            },
        });
    }

    function clearPhotoFileInput() {
        if (photoElement?.value) {
            photoElement.value = null;
        }
    }
</script>

<JetFormSection on:submitted={updateProfileInformation}>
    <svelte:fragment slot="title">
        Profile Information
    </svelte:fragment>

    <svelte:fragment slot="description">
        Update your account's profile information and email address.
    </svelte:fragment>

    <svelte:fragment slot="form">
        <!-- Profile Photo -->
        {#if $page.props.jetstream.managesProfilePhotos}
            <div class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                       bind:this={photoElement}
                       on:change={updatePhotoPreview}>

                <JetLabel id="photo" label="Photo"/>

                <!-- Current Profile Photo -->
                {#if !photoPreview}
                    <div class="mt-2">
                        <img src={user.profile_photo_url} alt={user.name} class="rounded-full size-20 object-cover"/>
                    </div>
                {/if}

                <!-- New Profile Photo Preview -->
                {#if photoPreview}
                    <div class="mt-2">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                          style={'background-image: url(\'' + photoPreview + '\');'}>
                    </span>
                    </div>
                {/if}

                <JetSecondaryButton class="mt-2 mr-2" type="button" on:clicked={selectNewPhoto}>
                    Select A New Photo
                </JetSecondaryButton>

                {#if user.profile_photo_path}
                    <JetSecondaryButton type="button" class="mt-2" on:clicked={deletePhoto}>
                        Remove Photo
                    </JetSecondaryButton>
                {/if}

                <JetInputError message={$form.errors.photo} class="mt-2"/>
            </div>
        {/if}

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <JetLabel id="name" label="Name"/>
            <JetInput id="name" type="text" class="mt-1 block w-full" bind:value={$form.name} autocomplete="name"/>
            <JetInputError message={$form.errors.name} class="mt-2"/>
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <JetLabel id="email" label="Email"/>
            <JetInput id="email" type="email" class="mt-1 block w-full" bind:value={$form.email}/>
            <JetInputError message={$form.errors.email} class="mt-2"/>
        </div>
    </svelte:fragment>

    <svelte:fragment slot="actions">
        <!--
        <jet-action-message :on="form.recentlySuccessful" class="mr-3">
            Saved.
        </jet-action-message>
        -->

        <JetButton class={$form.processing ? 'opacity-25' : ''} disabled={form.processing} type="submit">
            Save
        </JetButton>
    </svelte:fragment>
</JetFormSection>
