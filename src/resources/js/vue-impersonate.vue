<template>

    <div class="vue-impersonator" :class="{'vue-impersonator-opened' : show_menu}">

        <button @click.prevent="toggleMenu">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="17"><g fill="none" fill-rule="evenodd"><path fill="#000" d="M3.998 6.063L5.45 11.92l2.221 2.676 2.151-2.676.999-4.015.089-1.03v-.812z"/><path fill="#2C2C47" fill-rule="nonzero" d="M.05 11.495l1.27 5.473 8.309-.008-4.286-3.73-.939-3.633z"/><path fill="#41415D" fill-rule="nonzero" d="M14.898 11.376l-1.21 5.592H9.441L3.9 13.272l.64-2.291-2.776-1.055 2.391-3.281 1.288 5.273 2.219 2.679 2.13-2.64.618-2.486z"/><path fill="#565571" fill-rule="nonzero" d="M10.9 6.882l2.473 3.325-3.043.762.836 2.198-2.888 2.049-.621-.62 2.125-2.599 1.03-4.108.088-1.007z"/><path fill="#FFF" fill-rule="nonzero" d="M6.976 7.647l-1.074.7-.885-1.228 1.959.528zm.883 0l1.074.7.885-1.228-1.959.528z"/><g fill-rule="nonzero"><path fill="#41415D" d="M7.463 6.774V3.251l-3.748-.442S1.712 4.195 1.712 4.675c0 .48 2.962 2.1 5.751 2.1z"/><path fill="#2C2C47" d="M7.463 5.154V.768L5.654.003 4.912.11 3.67 3.914z"/><path fill="#565571" d="M7.463 5.921V4.586l-3.63-1.34-.467 1.061 4.097 1.614zm0-4.785L5.652.006 7.255.44l.208.056v.641z"/><path fill="#565571" d="M7.463 6.774V3.251l3.717-.442s1.987 1.385 1.987 1.866c0 .48-2.939 2.1-5.704 2.1z"/><path fill="#41415D" d="M7.463 5.152V.766L9.272 0l.742.108 1.242 3.804-3.793 1.24z"/><path fill="#FFF" d="M10.512 3.471c-.52.457-1.276.48-1.276.48l.315-1.505 1.691-.036s-.208.604-.73 1.061z"/><path fill="#6A6985" d="M7.463 5.925V4.589l3.631-1.34.466 1.061z"/><path fill="#565571" d="M7.103.683c0 .139.363.453.363.453L9.278.006 7.466.496s-.363.113-.363.188z"/></g></g></svg>
        </button>

        <label for="vue-impersonator-users" v-if="!isImpersonating && show_menu">
            Login as
        </label>

        <select v-if="!isImpersonating && show_menu" id="vue-impersonator-users" v-model="user" @change.prevent="startImpersonating">
            <option disabled selected v-if="!loaded">-- Fetching users --</option>
            <option disabled selected v-if="!users.length && loaded">-- No users found --</option>
            <option disabled selected v-if="failed && loaded">-- Couldnt fetch users --</option>
            <option v-for="user in users" :value="user">{{ user.display_name }}</option>
        </select>

        <div class="vue-impersonator-stop" v-if="isImpersonating && show_menu">
            <a :href="routes.leave">Stop impersonating</a>
        </div>

    </div>

</template>

<style scoped>

    .vue-impersonator {
        padding: 10px;
        position: fixed;
        left: 10px;
        bottom: 10px;
        z-index: 1000;
        transition: all .5s ease;
    }

    .vue-impersonator-opened {
        border: 1px solid #dae1e7;
        border-radius: 3px;
        background: #ffffff;
        box-shadow: 0 15px 30px 0 rgba(0, 0, 0, .11), 0 5px 15px 0 rgba(0, 0, 0, .08);
    }

    .vue-impersonator button {
        width: 15px;
        height: 17px;
        padding: 0;
        border: 0;
        background: none;
        -webkit-appearance: none;
        position: relative;
        top: 3px;
        outline: none;
    }

    .vue-impersonator label {
        margin: 0;
        padding: 0 10px;
        font-size: 13px;
        font-family: verdana;
        font-weight: normal;
    }

    .vue-impersonator select {
        display: inline-block;
        width: 200px;
        height: 30px;
        color: #9aa2a9;
    }

    .vue-impersonator-stop {
        display: inline-block;
        padding-left: 10px;
    }

    .vue-impersonator-stop a {
        color: #9aa2a9;
        font-size: 13px;
        font-family: verdana;
    }

</style>

<script>

    export default {

        props: {

            'is-impersonating': {
                type: Boolean,
                required: true,
            },

            'routes': {
                type: Object,
                required: true,
            },

        },

        data() {
            return {
                users: [],
                user: null,
                loaded: false,
                failed: false,
                show_menu: false,
            }
        },

        mounted() {
            if (this.isImpersonating) {
                this.toggleMenu();
            }
        },

        methods: {

            toggleMenu() {
                this.show_menu = !this.show_menu;

                if (this.show_menu) {
                    this.updateUserList();
                }

                return this;
            },

            startImpersonating() {
                const userId = this.user.id;
                const url = this.routes.take.replace('@userid', userId);

                window.location = url;
            },

            updateUserList() {
                const xhr = new XMLHttpRequest();

                xhr.open('GET', this.routes.users);

                xhr.withCredentials = true;
                xhr.setRequestHeader('X-CSRF-TOKEN', document.head.querySelector('meta[name="csrf-token"]').content)

                xhr.onload = response => {
                    this.loaded = true;

                    try {
                        let users = JSON.parse(xhr.responseText);

                        return this.users = users;
                    } catch (err) {
                        this.failed = true

                        return console.error(err);
                    }
                }

                xhr.onerror = err => {
                    this.failed = true;
                    this.loaded = true;

                    return console.error(err);
                }

                xhr.send();
            },

        }
    }

</script>
