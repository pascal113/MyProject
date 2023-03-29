<template>
    <ul class="nav navbar-nav">
        <li
            v-for="item in filteredItems"
            :key="item.id"
            :class="classes(item)"
        >
            <a
                v-if="item.url || item.route || item.children.length"
                :target="item.target"
                :href="item.children.length > 0 ? '#' + item.id + '-dropdown-element' : item.href"
                :style="'color:' + color(item)"
                :data-toggle="item.children.length > 0 ? 'collapse' : false"
                :aria-expanded="item.children.length > 0 ? String(item.active) : false"
            >
                <span :class="'icon ' + item.icon_class" />
                <span class="title">{{ item.title }}</span>
            </a>
            <a
                v-else
                :style="'color:' + color(item)"
                :aria-expanded="false"
            >
                <span :class="'icon ' + item.icon_class" />
                <span class="title">{{ item.title }}</span>
            </a>
            <div
                v-if="item.children.length > 0"
                :id="item.id + '-dropdown-element'"
                :class="'panel-collapse collapse' + (item.active ? ' in' : ' ')"
            >
                <div class="panel-body">
                    <admin-menu-bbc-com :items="item.children" />
                </div>
            </div>
        </li>
    </ul>
</template>
<script>
export default {
    props: {
        canViewAdminLanding: {
            type: Boolean,
            default: true,
        },
        items: {
            type: Array,
            default: () => [],
        },
    },
    computed: {
        filteredItems: function() {
            const self = this

            const newItems = this.items.reduce(function(acc, item) {
                if (!self.canViewAdminLanding && item.title === 'Admin Landing Page') {
                    return acc
                }

                if (item.url || item.route || item.children.length || item.active) {
                    acc.push(item)
                }

                return acc
            }, [])

            return newItems.reduce(function(acc, item, i) {
                if (item.active && !item.route) {
                    // This is a "section heading" such as "CONTENT" or "ADMIN"
                    const nextSectionHeadingIndex = self.items.findIndex(function(item, nextI) {
                        return nextI > i && item.active
                    })
                    const section = self.items.slice(i + 1, nextSectionHeadingIndex || undefined)

                    if (!section.length) {
                        return acc
                    }
                }

                acc.push(item)

                return acc
            }, [])
        },
    },
    methods: {
        classes: function(item) {
            var classes = []
            if (item.children.length > 0) {
                classes.push('dropdown')
            }
            if (item.active) {
                classes.push('active')
            }

            return classes.join(' ')
        },
        color: function(item) {
            if (item.color && item.color != '#000000') {
                return item.color
            }

            return ''
        },
    },
}
</script>
