<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Building Materials Store</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body class="store-page">
<header>
  <h1>🏗 Building Materials Store</h1>
  <div class="header-buttons">
    <button id="cart-btn" class="cart-btn">🛒 Cart (<span id="cart-count">0</span>)</button>
    <?php if ($isLoggedIn): ?>
      <button id="logout-btn" class="logout-btn">🚪 Logout</button>
    <?php else: ?>
      <a href="login.php" class="logout-btn">🔑 Login</a>
    <?php endif; ?>
  </div>
</header>

<main id="products-container">
  <!-- Products will be added here dynamically -->
</main>

<!-- ✅ Toast container -->
<div id="toast" class="toast"></div>

<script>
// ✅ Toast Function
function showToast(message, color = "#333") {
  const toast = document.getElementById("toast");
  toast.textContent = message;
  toast.style.background = color;
  toast.classList.add("show");

  setTimeout(() => {
    toast.classList.remove("show");
  }, 2000);
}

// ✅ Fetch products
fetch("get_products.php")
  .then(res => res.json())
  .then(products => {
    const container = document.getElementById("products-container");
    container.innerHTML = "";

    if (products.length === 0) {
      container.innerHTML = "<p>No products found.</p>";
      return;
    }

    products.forEach(product => {
      const card = document.createElement("div");
      card.classList.add("product-card");
      card.innerHTML = `
        <img src="images/${product.image}" alt="${product.name}" />
        <h3>${product.name}</h3>
        <p>₹${product.price}</p>
        <button class="add-btn" onclick="addToCart(${product.id})">Add to Cart</button>
      `;
      container.appendChild(card);
    });
  })
  .catch(err => {
    console.error("Error loading products:", err);
    document.getElementById("products-container").innerHTML = "<p>Error loading products.</p>";
  });

// ✅ Add to Cart (with login check)
function addToCart(productId) {
  const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
  if (!isLoggedIn) {
    showToast("Please log in to add items to your cart!", "#ff5252");
    return;
  }

  fetch("add_to_cart.php?id=" + productId)
    .then(res => res.json())
    .then(data => {
      showToast(data.message, "#4CAF50");
      updateCart();
    })
    .catch(err => console.error("Error adding to cart:", err));
}

// ✅ Update cart count
function updateCart() {
  fetch("get_cart.php")
    .then(res => res.json())
    .then(data => {
      if (Array.isArray(data)) {
        const totalQuantity = data.reduce((sum, item) => sum + parseInt(item.quantity), 0);
        document.getElementById("cart-count").innerText = totalQuantity;
      }
    })
    .catch(err => console.error("Error updating cart count:", err));
}

// ✅ Cart button
document.getElementById("cart-btn").addEventListener("click", () => {
  window.location.href = "cart.php";
});

// ✅ Logout button
const logoutBtn = document.getElementById("logout-btn");
if (logoutBtn) {
  logoutBtn.addEventListener("click", () => {
    fetch("logout.php")
      .then(() => {
        showToast("Logged out successfully!", "#ff5252");
        setTimeout(() => {
          window.location.href = "login.php";
        }, 800);
      })
      .catch(err => console.error("Logout error:", err));
  });
}

updateCart();
</script>
</body>
</html>