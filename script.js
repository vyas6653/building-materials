// =====================
// PRODUCT LIST
// =====================
const products = [
  { id: 1, name: "Cement", price: 350, image: "images/cement.jpeg" },
  { id: 2, name: "Steel Rod", price: 500, image: "images/steel_rod.jpeg" },
  { id: 3, name: "Bricks", price: 200, image: "images/bricks.jpeg" },
];

// =====================
// SHOW PRODUCTS ON INDEX PAGE
// =====================
if (document.getElementById("product-list")) {
  const productList = document.getElementById("product-list");

  products.forEach((p) => {
    const card = document.createElement("div");
    card.classList.add("product-card");
    card.innerHTML = `
      <img src="${p.image}" alt="${p.name}">
      <h3>${p.name}</h3>
      <p>₹${p.price}</p>
      <button onclick="addToCart(${p.id})">Add to Cart</button>
    `;
    productList.appendChild(card);
  });
}

// =====================
// ADD TO CART FUNCTION
// =====================
function addToCart(id) {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  const product = products.find((p) => p.id === id);
  const existingItem = cart.find((item) => item.id === id);

  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    cart.push({ ...product, quantity: 1 });
  }

  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();
}

// =====================
// UPDATE CART COUNT
// =====================
function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  const cartCount = document.getElementById("cart-count");
  if (cartCount) cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
}

// =====================
// SHOW CART ITEMS ON CART PAGE
// =====================
if (document.getElementById("cart-items")) {
  const cartItemsDiv = document.getElementById("cart-items");
  const cart = JSON.parse(localStorage.getItem("cart")) || [];

  if (cart.length === 0) {
    cartItemsDiv.innerHTML = "<p>Your cart is empty.</p>";
  } else {
    cart.forEach((item) => {
      const div = document.createElement("div");
      div.classList.add("cart-item");
      div.innerHTML = `
        <img src="${item.image}" alt="${item.name}">
        <h3>${item.name}</h3>
        <p>Price: ₹${item.price}</p>
        <p>Quantity: ${item.quantity}</p>
      `;
      cartItemsDiv.appendChild(div);
    });
  }

  const backButton = document.getElementById("back-btn");
  if (backButton) {
    backButton.addEventListener("click", () => {
      window.location.href = "index.php";
    });
  }
}

// =====================
// INITIAL LOAD
// =====================
updateCartCount();