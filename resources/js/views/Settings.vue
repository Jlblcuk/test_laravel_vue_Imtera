<template>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo.jpg" class="logo-img">
                <span>Daily Grow</span>
            </div>

            <div class="account-name">Название аккаунта
                <div class="settings">
                    <button @click="logout" class="logout-button">Выйти</button>
                </div>
            </div>

            <nav class="menu">
                <router-link to="/reviews" class="menu-item">
                    Отзывы
                </router-link>

                <router-link to="/settings" class="menu-item active">
                    Настройка
                </router-link>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-wrapper">
                <h1 class="page-title">Подключить Яндекс</h1>

                <div class="settings-card">
                    <label class="input-label">
                        Укажите ссылку на Яндекс, пример:
                        <span class="example-link">https://yandex.ru/maps/org/gorkogo_papai/1010501395/reviews/</span>
                    </label>

                    <input
                        v-model="yandexUrl"
                        type="text"
                        class="url-input"
                        placeholder="https://yandex.ru/maps/org/название_организации/1010501395/reviews/"
                    />

                    <button
                        @click="saveSettings"
                        class="save-button"
                        :disabled="loading"
                    >
                        {{ loading ? 'Сохранение...' : 'Сохранить' }}
                    </button>

                    <div v-if="message" :class="['message', messageType]">
                        {{ message }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script src="./Settings.js"></script>
<style src="../../css/settings.css" scoped></style>
