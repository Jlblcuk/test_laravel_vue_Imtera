import axios from 'axios';

export default {
    name: 'Reviews',
    data() {
        return {
            reviews: [],
            stats: {
                rating: 0,
                reviews_count: 0
            },
            loading: true,
            error: null
        };
    },
    mounted() {
        this.loadReviews();
    },
    methods: {
        async loadReviews() {
            try {
                const response = await axios.get('/api/reviews');

                if (response.data.reviews) {
                    this.reviews = response.data.reviews;
                    this.stats.rating = response.data.rating || 0;
                    this.stats.reviews_count = response.data.reviews_count || 0;
                } else {
                    this.error = 'Нет данных';
                }
            } catch (error) {
                console.error('Error loading reviews:', error);
                this.error = 'Ошибка загрузки отзывов';
            } finally {
                this.loading = false;
            }
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();

            return `${day}.${month}.${year}`;
        },

        async logout() {
            try {
                await axios.post('/logout', {}, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                window.location.href = '/login';
            } catch (error) {
                console.error('Logout error:', error);
                window.location.href = '/login';
            }
        }
    }
};
