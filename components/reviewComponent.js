function createReviewComponent(movie) {
    return `
        <div class="review">
            <h2>${movie.title}</h2>
            <p>Rating: ${movie.rating}</p>
            <p>${movie.review}</p>
        </div>
    `;
}
