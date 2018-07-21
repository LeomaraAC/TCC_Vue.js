<template>
    <div>
        <nav aria-label="Page navigation" v-if="souce.last_page > 1">
        <ul class="pagination justify-content-end">
            <li :class="{'page-item': true, ' disabled': souce.current_page == 1}">
                <a class="page-link" href="#" aria-label="Previous" @click="navigate($event, souce.current_page - 1)">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <li v-for="page in pages" :key="page" :class="{'page-item': true, 'active': souce.current_page == page}">
                <a class="page-link" href="#" @click="navigate($event, page)">{{ page }}</a>
            </li>
            <li :class="{'page-item': true, ' disabled': souce.current_page == souce.last_page}">
                <a class="page-link" href="#" aria-label="Next" @click="navigate($event, souce.current_page + 1)">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
    </nav>
    </div>
</template>
<script>
    export default {
        props: ['souce', 'pages'],
        methods: {
            navigate(ev, page) {
                ev.preventDefault();
                this.$emit("navigate", page);
            }
        }
    }
</script>
