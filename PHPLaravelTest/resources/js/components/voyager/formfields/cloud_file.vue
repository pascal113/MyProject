<template>
    <div>
        <div
            v-if="value"
            class="form-group"
        >
            <input
                id="type-current"
                v-model="type"
                type="radio"
                class="form-check-input"
                name="type"
                value="current"
            >
            <label
                class="form-check-label"
                for="type-current"
            >
                Current file:
                <a
                    v-if="fileUrl"
                    :href="fileUrl"
                    target="_blank"
                >
                    {{ value }}
                </a>
                <span v-else>{{ value }}</span>
            </label>
            <label
                v-if="thumbnail"
                class="form-check-label"
                for="type-current"
            >
                <small>
                    (Current thumbnail:
                    <a
                        v-if="thumbnailUrl"
                        :href="thumbnailUrl"
                        target="_blank"
                    >
                        {{ thumbnail }}
                    </a>
                    <span v-else>{{ value }}</span>
                    )
                </small>
            </label>
            <input
                v-if="type === 'current'"
                v-model="value"
                type="hidden"
                name="path"
            >
        </div>

        <p class="form-text text-muted">
            Upload a new file or enter a URL
        </p>
        <div class="form-group">
            <input
                id="type-upload"
                v-model="type"
                type="radio"
                class="form-check-input"
                name="type"
                value="upload"
            >

            <label
                class="form-check-label"
                for="type-upload"
            >Upload a new file</label>
            <br>
            <div v-if="type === 'upload'">
                <label
                    class="control-label"
                    for="file"
                >Main File</label>
                <input
                    id="file"
                    type="file"
                    name="file"
                    class="form-control-file"
                >
                <br>
                <label
                    class="control-label"
                    for="thumbnail"
                >Thumbnail</label>
                <i>(optional)</i>
                <p
                    id="canonical_thumbnailHelpBlock"
                    class="form-text text-muted"
                >
                    Thumbnails are only used for video files, are are displayed while the video
                    loads and before it is played.
                </p>
                <p
                    id="canonical_thumbnailHelpBlock-2"
                    class="form-text text-muted"
                >
                    (1280x720px)
                </p>
                <input
                    id="thumbnail"
                    type="file"
                    name="thumbnail"
                    class="form-control-file"
                >
            </div>
        </div>

        <div class="form-group">
            <input
                id="type-path"
                v-model="type"
                type="radio"
                class="form-check-input"
                name="type"
                value="path"
            >
            <label
                class="form-check-label"
                for="type-path"
            >Enter a URL</label>
            <p
                v-if="type === 'path'"
                class="form-text text-muted"
            >
                Enter the URL of either an mp4 file hosted anywhere, or a YouTube embed URL such as
                https://www.youtube.com/embed/dQw4w9WgXcQ
            </p>
            <input
                v-if="type === 'path'"
                v-model="url"
                type="text"
                class="form-control"
                name="path"
                @change="enforceValidUrl"
            >
        </div>
    </div>
</template>

<script>
export default {
    props: {
        fileUrl: {
            type: String,
            default: null,
        },
        name: {
            type: String,
            required: true,
        },
        old: {
            type: String,
            default: null,
        },
        options: {
            type: Object,
            default: () => ({}),
        },
        row: {
            type: Object,
            required: true,
        },
        thumbnail: {
            type: String,
            default: null,
        },
        thumbnailUrl: {
            type: String,
            default: null,
        },
    },

    data: () => ({
        type: 'current',
        url: null,
        value: null,
    }),

    mounted() {
        this.value = this.old

        if (!this.value) {
            this.type = 'upload'
        }
    },

    methods: {
        enforceValidUrl: function() {
            if (this.url.match(/^(http|ftp)[s]?/)) {
                return
            }

            this.url = `http://${this.url}`
        },
    },
}
</script>
