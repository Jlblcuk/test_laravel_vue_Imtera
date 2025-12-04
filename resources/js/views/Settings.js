import axios from 'axios';

export default {
    name: 'Settings',
    data() {
        return {
            yandexUrl: '',
            loading: false,
            message: '',
            messageType: 'success'
        };
    },
    mounted() {
        this.loadSettings();
    },
    methods: {
        async loadSettings() {
            try {
                const response = await axios.get('/api/settings');
                if (response.data.yandex_url) {
                    this.yandexUrl = response.data.yandex_url;
                }
            } catch (error) {
                console.error('Error loading settings:', error);
            }
        },

        async saveSettings() {
            if (!this.yandexUrl) {
                this.showMessage('Введите ссылку', 'error');
                return;
            }

            this.loading = true;
            this.message = '';

            try {
                await axios.post('/api/settings', {
                    yandex_url: this.yandexUrl
                });

                this.showMessage('Настройки сохранены', 'success');

                setTimeout(() => {
                    this.$router.push('/reviews');
                }, 1000);
            } catch (error) {
                this.showMessage('Ошибка при сохранении', 'error');
                console.error('Error saving settings:', error);
            } finally {
                this.loading = false;
            }
        },

        showMessage(text, type) {
            this.message = text;
            this.messageType = type;
            setTimeout(() => {
                this.message = '';
            }, 3000);
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
