<div x-data="productReviews()" x-init="init()">
    <!-- Reviews Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
        <!-- Overall Rating -->
        <div class="text-center">
            <div class="text-6xl font-bold text-gray-900 mb-2" x-text="averageRating.toFixed(1)"></div>
            <div class="flex items-center justify-center mb-2">
                <template x-for="i in 5" :key="i">
                    <i class="text-2xl" :class="i <= Math.round(averageRating) ? 'bi bi-star-fill text-yellow-400' : 'bi bi-star text-gray-300'"></i>
                </template>
            </div>
            <p class="text-gray-600"><span x-text="totalReviews"></span> avaliações</p>
        </div>

        <!-- Rating Distribution -->
        <div class="md:col-span-2">
            <template x-for="star in [5, 4, 3, 2, 1]" :key="star">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-sm font-medium text-gray-700 w-20"><span x-text="star"></span> estrelas</span>
                    <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-yellow-400 transition-all duration-300" 
                             :style="`width: ${getRatingPercentage(star)}%`"></div>
                    </div>
                    <span class="text-sm text-gray-600 w-12 text-right" x-text="`${getRatingPercentage(star)}%`"></span>
                </div>
            </template>
        </div>
    </div>

    <!-- Write Review Button -->
    <div class="border-t border-gray-200 pt-8 mb-8">
        <button @click="showReviewForm = !showReviewForm" 
                class="bg-primary text-white px-8 py-4 rounded-xl hover:bg-red-700 transition-all duration-200 font-semibold flex items-center gap-2 mx-auto shadow-lg hover:shadow-xl hover:scale-105">
            <i :class="showReviewForm ? 'bi bi-x-circle-fill' : 'bi bi-pencil-square'" class="text-xl"></i>
            <span x-text="showReviewForm ? 'Cancelar Avaliação' : 'Escrever Avaliação'"></span>
        </button>
    </div>

    <!-- Review Form -->
    <div x-show="showReviewForm" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="bg-gray-50 rounded-xl p-6 mb-8"
         style="display: none;">
        <h3 class="text-xl font-bold mb-4">Sua Avaliação</h3>
        <form @submit.prevent="submitReview()">
            <!-- Rating Stars -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Avaliação *</label>
                <div class="flex gap-2">
                    <template x-for="i in 5" :key="i">
                        <button type="button" @click="formData.rating = i" 
                                class="text-3xl transition-colors duration-200"
                                :class="i <= formData.rating ? 'text-yellow-400' : 'text-gray-300'">
                            <i class="bi bi-star-fill"></i>
                        </button>
                    </template>
                </div>
                <p x-show="errors.rating" class="text-red-500 text-sm mt-1" x-text="errors.rating" style="display: none;"></p>
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Título (opcional)
                </label>
                <input type="text" x-model="formData.title" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                       placeholder="Resuma sua experiência">
            </div>

            <!-- Comment -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Comentário *
                </label>
                <textarea x-model="formData.comment" rows="5"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                          placeholder="Compartilhe sua experiência com este produto (mínimo 10 caracteres)"></textarea>
                <p x-show="errors.comment" class="text-red-500 text-sm mt-1" style="display: none;">
                    <span x-text="errors.comment"></span>
                </p>
            </div>

            <!-- Name & Email (for guest users) -->
            @guest
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nome *
                    </label>
                    <input type="text" x-model="formData.customer_name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                           placeholder="Seu nome completo">
                    <p x-show="errors.customer_name" class="text-red-500 text-sm mt-1" style="display: none;">
                        <span x-text="errors.customer_name"></span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        E-mail *
                    </label>
                    <input type="email" x-model="formData.customer_email" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                           placeholder="seu@email.com">
                    <p x-show="errors.customer_email" class="text-red-500 text-sm mt-1" style="display: none;">
                        <span x-text="errors.customer_email"></span>
                    </p>
                </div>
            </div>
            @endguest

            <!-- Submit Button -->
            <div class="flex flex-col sm:flex-row justify-end gap-3">
                <button type="button" @click="showReviewForm = false" 
                        class="px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-semibold">
                    <i class="bi bi-x-circle mr-2"></i>
                    Cancelar
                </button>
                <button type="submit" :disabled="submitting"
                        class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-red-700 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 font-semibold shadow-lg hover:shadow-xl">
                    <span x-show="!submitting" class="flex items-center gap-2">
                        <i class="bi bi-send-fill"></i>
                        Enviar Avaliação
                    </span>
                    <span x-show="submitting" class="flex items-center gap-2" style="display: none;">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Enviando...
                    </span>
                </button>
            </div>
        </form>
    </div>

    <!-- Reviews List -->
    <div class="space-y-6">
        <template x-for="review in reviews" :key="review.id">
            <div class="bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-primary/30 hover:shadow-lg transition-all duration-200">
                <!-- Review Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary to-red-600 text-white rounded-full flex items-center justify-center font-bold text-lg shadow-md">
                                <span x-text="review.customer_name.charAt(0).toUpperCase()"></span>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 text-lg" x-text="review.customer_name"></p>
                                <p class="text-sm text-gray-500 flex items-center gap-1">
                                    <i class="bi bi-calendar3"></i>
                                    <span x-text="formatDate(review.created_at)"></span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <template x-for="i in 5" :key="i">
                                <i class="text-xl" :class="i <= review.rating ? 'bi bi-star-fill text-yellow-400' : 'bi bi-star text-gray-300'"></i>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Review Content -->
                <div class="mb-4">
                    <h4 x-show="review.title" class="font-bold text-gray-900 text-lg mb-2" x-text="review.title"></h4>
                    <p class="text-gray-700 leading-relaxed" x-text="review.comment"></p>
                </div>

                <!-- Review Actions -->
                <div class="pt-4 border-t border-gray-100 flex items-center gap-4">
                    <button @click="markHelpful(review.id)" 
                            class="text-sm text-gray-600 hover:text-primary transition-all duration-200 flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-50 font-medium">
                        <i class="bi bi-hand-thumbs-up text-lg"></i>
                        <span>Útil (<span x-text="review.helpful_count"></span>)</span>
                    </button>
                </div>
            </div>
        </template>

        <!-- Load More Button -->
        <div x-show="hasMore" class="text-center mt-8" style="display: none;">
            <button @click="loadMore()" :disabled="loading"
                    class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-primary transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed font-semibold flex items-center gap-2 mx-auto">
                <span x-show="!loading" class="flex items-center gap-2">
                    <i class="bi bi-arrow-down-circle"></i>
                    Carregar mais avaliações
                </span>
                <span x-show="loading" class="flex items-center gap-2" style="display: none;">
                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Carregando...
                </span>
            </button>
        </div>

        <!-- No Reviews -->
        <div x-show="reviews.length === 0 && !loading" class="text-center py-12">
            <i class="bi bi-chat-square-text text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Nenhuma avaliação ainda. Seja o primeiro a avaliar!</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function productReviews() {
    return {
        productId: {{ $product->id }},
        reviews: [],
        totalReviews: {{ $reviewsStats['total'] }},
        averageRating: {{ $reviewsStats['average'] }},
        ratingDistribution: {
            5: {{ $reviewsStats['5_star'] }},
            4: {{ $reviewsStats['4_star'] }},
            3: {{ $reviewsStats['3_star'] }},
            2: {{ $reviewsStats['2_star'] }},
            1: {{ $reviewsStats['1_star'] }}
        },
        currentPage: 1,
        lastPage: 1,
        hasMore: false,
        loading: false,
        submitting: false,
        showReviewForm: false,
        formData: {
            rating: 5,
            title: '',
            comment: '',
            customer_name: '',
            customer_email: ''
        },
        errors: {},

        init() {
            this.loadReviews();
        },

        async loadReviews() {
            this.loading = true;
            try {
                const response = await fetch(`/api/products/${this.productId}/reviews?page=${this.currentPage}`);
                const data = await response.json();
                
                this.reviews = [...this.reviews, ...data.data];
                this.lastPage = data.last_page;
                this.hasMore = this.currentPage < this.lastPage;
            } catch (error) {
                // Erro ao carregar avaliações
            }
            this.loading = false;
        },

        loadMore() {
            this.currentPage++;
            this.loadReviews();
        },

        async submitReview() {
            this.errors = {};
            this.submitting = true;

            // Enviando review

            try {
                const response = await fetch(`/api/products/${this.productId}/reviews`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();
                // Resposta da API

                if (response.ok && data.success) {
                    // Adicionar review ao início da lista
                    this.reviews.unshift(data.review);
                    this.totalReviews++;
                    
                    // Recalcular média
                    this.recalculateAverage();
                    
                    // Resetar formulário
                    this.resetForm();
                    this.showReviewForm = false;
                    
                    // Mostrar mensagem de sucesso
                    alert('Avaliação enviada com sucesso!');
                } else {
                    // Erros de validação
                    this.errors = data.errors || {};
                    
                    // Mostrar primeiro erro em alert
                    if (data.errors) {
                        const firstError = Object.values(data.errors)[0];
                        alert(Array.isArray(firstError) ? firstError[0] : firstError);
                    }
                }
            } catch (error) {
                // Erro ao enviar avaliação
                alert('Erro ao enviar avaliação. Tente novamente.');
            }
            this.submitting = false;
        },

        async markHelpful(reviewId) {
            try {
                const response = await fetch(`/api/reviews/${reviewId}/helpful`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    const review = this.reviews.find(r => r.id === reviewId);
                    if (review) {
                        review.helpful_count = data.helpful_count;
                    }
                }
            } catch (error) {
                // Erro ao marcar como útil
            }
        },

        getRatingPercentage(star) {
            if (this.totalReviews === 0) return 0;
            return Math.round((this.ratingDistribution[star] / this.totalReviews) * 100);
        },

        recalculateAverage() {
            const total = this.reviews.reduce((sum, review) => sum + review.rating, 0);
            this.averageRating = total / this.reviews.length;
        },

        resetForm() {
            this.formData = {
                rating: 5,
                title: '',
                comment: '',
                customer_name: '',
                customer_email: ''
            };
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }
    };
}
</script>
@endpush

