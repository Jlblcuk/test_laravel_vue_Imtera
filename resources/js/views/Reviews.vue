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
                <router-link to="/reviews" class="menu-item active">
                    Отзывы
                </router-link>

                <router-link to="/settings" class="menu-item">
                    Настройка
                </router-link>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-wrapper">
                <div class="header-section">
                    <div class="source-badge">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="#FF0000">
                            <circle cx="8" cy="8" r="8"/>
                        </svg>
                        Яндекс Карты
                    </div>
                </div>

                <div v-if="loading" class="loading">Загрузка...</div>

                <div v-else-if="error" class="error-message">{{ error }}</div>

                <div v-else class="reviews-container">
                    <!-- Reviews List -->
                    <div class="reviews-list">
                        <div v-for="review in reviews" :key="review.date" class="review-card">
                            <div class="review-header">
                                <div class="review-date">{{ formatDate(review.date) }}</div>
                                <div class="review-author">{{ review.author }}</div>
                            </div>

                            <div class="review-stars">
                                <span v-for="n in 5" :key="n" class="star" :class="{ filled: n <= review.rating }">★</span>
                            </div>

                            <div class="review-text">{{ review.text }}</div>
                        </div>
                    </div>

                    <!-- Rating Sidebar -->
                    <div class="rating-sidebar">
                        <div class="rating-display">
                            <div class="rating-number">{{ stats.rating }}</div>
                            <div class="rating-stars">
                                <span v-for="n in 5" :key="n" class="star" :class="{ filled: n <= Math.round(stats.rating), half: n === Math.ceil(stats.rating) && stats.rating % 1 !== 0 }">★</span>
                            </div>
                            <div class="rating-count">Всего отзывов: {{ stats.reviews_count }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script src="./Reviews.js"></script>
<style src="../../css/reviews.css" scoped></style>
