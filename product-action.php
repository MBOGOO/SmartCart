<?php

session_start();
include 'connection/connect.php'; // DB connection

if (! empty($_GET['action'])) {
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

    switch ($_GET['action']) {
        /* ===============================
           ADD TO CART (No stock change yet)
        =============================== */
        case 'add':
            if (! empty($quantity) && $productId > 0) {
                // Fetch product details
                $stmt = $db->prepare('SELECT * FROM items WHERE d_id = ?');
                $stmt->bind_param('i', $productId);
                $stmt->execute();
                $productDetails = $stmt->get_result()->fetch_object();

                if ($productDetails) {
                    if ($productDetails->stock < $quantity) {
                        echo "<script>alert('❌ Not enough stock available! Only {$productDetails->stock} left.'); window.history.back();</script>";
                        exit;
                    }

                    // Prepare item array
                    $itemArray = [
                        $productDetails->d_id => [
                            'title' => $productDetails->title,
                            'd_id' => $productDetails->d_id,
                            'quantity' => $quantity,
                            'price' => $productDetails->price,
                        ],
                    ];

                    // Store item in session
                    if (! empty($_SESSION['cart_item'])) {
                        if (in_array($productDetails->d_id, array_keys($_SESSION['cart_item']))) {
                            foreach ($_SESSION['cart_item'] as $k => $v) {
                                if ($productDetails->d_id == $k) {
                                    if (empty($_SESSION['cart_item'][$k]['quantity'])) {
                                        $_SESSION['cart_item'][$k]['quantity'] = 0;
                                    }
                                    $_SESSION['cart_item'][$k]['quantity'] += $quantity;
                                }
                            }
                        } else {
                            $_SESSION['cart_item'] = $_SESSION['cart_item'] + $itemArray;
                        }
                    } else {
                        $_SESSION['cart_item'] = $itemArray;
                    }
                }
            }
            break;

            /* ===============================
               REMOVE ITEM FROM CART
            =============================== */
        case 'remove':
            if (! empty($_SESSION['cart_item'])) {
                foreach ($_SESSION['cart_item'] as $k => $v) {
                    if ($productId == $v['d_id']) {
                        unset($_SESSION['cart_item'][$k]);
                    }
                }
            }
            break;

            /* ===============================
               EMPTY CART
            =============================== */
        case 'empty':
            unset($_SESSION['cart_item']);
            break;

            /* ===============================
               CHECKOUT
            =============================== */
        case 'check':
            header('location:checkout.php');
            break;
    }
}
