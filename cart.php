<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="cart-page">

  <header>
    <h1>🛒 Your Cart</h1>
    <a href="index.php" class="back-btn">⬅ Back to Store</a>
  </header>

  <main>
    <div id="cart-container" class="cart-container">Loading your cart...</div>
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

  // ✅ Fetch and display cart items
  async function loadCart() {
    const cartContainer = document.getElementById("cart-container");

    try {
      const res = await fetch("get_cart.php");
      const data = await res.json();

      cartContainer.innerHTML = "";

      if (data.error) {
        cartContainer.innerHTML = `<p class="error">${data.error}</p>`;
        return;
      }

      if (data.length === 0) {
        cartContainer.innerHTML = "<p class='empty-cart'>Your cart is empty.</p>";
        return;
      }

      let total = 0;

      data.forEach(item => {
        total += item.price * item.quantity;
        const div = document.createElement("div");
        div.classList.add("cart-item-page");

        div.innerHTML = `
          <img src="images/${item.image}" alt="${item.name}">
          <div class="cart-info">
            <h3>${item.name}</h3>
            <p>Price: ₹${item.price}</p>
            <p>Quantity: ${item.quantity}</p>
            <p><strong>Total: ₹${item.price * item.quantity}</strong></p>
            <button class="remove-btn" onclick="removeFromCart(${item.id})">Remove</button>
          </div>
        `;
        cartContainer.appendChild(div);
      });

      const totalDiv = document.createElement("div");
      totalDiv.classList.add("cart-total");
      totalDiv.innerHTML = `<h2>Grand Total: ₹${total}</h2>`;
      cartContainer.appendChild(totalDiv);

    } catch (error) {
      cartContainer.innerHTML = "<p>Error loading cart.</p>";
      console.error(error);
    }
  }

  // ✅ Remove one quantity per click with toast
  async function removeFromCart(productId) {
    const res = await fetch("remove_from_cart.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ product_id: productId })
    });

    const data = await res.json();

    if (data.success) {
      showToast(" Removed from cart", "#e63946");
      loadCart(); // instantly refresh cart
    } else {
      showToast("⚠ Something went wrong", "#f4a261");
    }
  }

  loadCart();
  </script>

</body>
</html>