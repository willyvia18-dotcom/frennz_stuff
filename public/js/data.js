/* ============================================================
   FRENNZ.STUFF — data.js
   Data produk contoh + kategori + testimoni (ID + EN). Produk yang
   diubah lewat panel admin disimpan sebagai override di localStorage
   supaya perubahan langsung terlihat di toko.
   ============================================================ */

const DEFAULT_PRODUCTS = [
  {
    id: "f01", name: "Essential Oversized Hoodie", category: "Hoodie",
    price: 429000, salePrice: 349000,
    colors: [{name:"Hitam",hex:"#111110"},{name:"Krem",hex:"#e7ddc9"},{name:"Abu",hex:"#9a9691"}],
    sizes: ["S","M","L","XL"], stock: { S: 6, M: 10, L: 8, XL: 2 },
    rating: 4.8, reviews: 156,
    desc: "Hoodie oversized dengan fleece 340gsm brushed-interior. Dipotong dengan siluet drop-shoulder yang bersih, minim aksen, dan dirancang untuk tahan lama.",
    descEn: "Oversized hoodie with 340gsm brushed-interior fleece. Cut with a clean drop-shoulder silhouette, minimal accents, and designed to last.",
    images: ["https://picsum.photos/seed/frennz-f01a/900/1150","https://picsum.photos/seed/frennz-f01b/900/1150","https://picsum.photos/seed/frennz-f01c/900/1150"],
    tag: "SALE", bestseller: true
  },
  {
    id: "f02", name: "Signature Tee — Heavyweight", category: "T-Shirt",
    price: 219000, salePrice: null,
    colors: [{name:"Putih",hex:"#ffffff"},{name:"Hitam",hex:"#111110"},{name:"Sand",hex:"#cbbfa4"}],
    sizes: ["S","M","L","XL"], stock: { S: 14, M: 18, L: 12, XL: 7 },
    rating: 4.9, reviews: 302,
    desc: "T-shirt garment-dyed 240gsm dengan jahitan rapi dan kerah yang tidak melar. Menjadi dasar dari setiap tampilan.",
    descEn: "240gsm garment-dyed tee with neat stitching and a collar that won't stretch out. The foundation of every outfit.",
    images: ["https://picsum.photos/seed/frennz-f02a/900/1150","https://picsum.photos/seed/frennz-f02b/900/1150"],
    tag: "NEW", bestseller: true
  },
  {
    id: "f03", name: "Minimal Crewneck", category: "Crewneck",
    price: 379000, salePrice: null,
    colors: [{name:"Krem",hex:"#e7ddc9"},{name:"Navy",hex:"#28303f"}],
    sizes: ["M","L","XL"], stock: { M: 6, L: 6, XL: 4 },
    rating: 4.6, reviews: 84,
    desc: "Crewneck fleece dengan rib tebal di kerah dan pergelangan. Dibuat tanpa cetakan besar — hanya bordir logo kecil di dada.",
    descEn: "Fleece crewneck with thick ribbing at the collar and cuffs. Made without big prints — just a small embroidered logo on the chest.",
    images: ["https://picsum.photos/seed/frennz-f03a/900/1150","https://picsum.photos/seed/frennz-f03b/900/1150"],
    tag: null, bestseller: false
  },
  {
    id: "f04", name: "Relaxed Fit Shirt", category: "Kemeja",
    price: 329000, salePrice: 279000,
    colors: [{name:"Putih",hex:"#ffffff"},{name:"Sage",hex:"#a7b09a"}],
    sizes: ["S","M","L","XL"], stock: { S: 4, M: 0, L: 5, XL: 3 },
    rating: 4.5, reviews: 58,
    desc: "Kemeja katun relaxed-fit dengan drape ringan dan kancing tanduk. Cocok dikenakan longgar atau dimasukkan.",
    descEn: "Relaxed-fit cotton shirt with a light drape and horn buttons. Works worn loose or tucked in.",
    images: ["https://picsum.photos/seed/frennz-f04a/900/1150","https://picsum.photos/seed/frennz-f04b/900/1150"],
    tag: "SALE", bestseller: false
  },
  {
    id: "f05", name: "Tapered Cargo Pants", category: "Celana",
    price: 459000, salePrice: null,
    colors: [{name:"Hitam",hex:"#111110"},{name:"Olive",hex:"#5c5c47"}],
    sizes: ["29","30","32","34"], stock: { "29": 5, "30": 7, "32": 6, "34": 2 },
    rating: 4.7, reviews: 121,
    desc: "Celana cargo tapered dengan enam kantong fungsional dan bahan ripstop ringan tahan air.",
    descEn: "Tapered cargo pants with six functional pockets in lightweight water-resistant ripstop fabric.",
    images: ["https://picsum.photos/seed/frennz-f05a/900/1150","https://picsum.photos/seed/frennz-f05b/900/1150"],
    tag: null, bestseller: true
  },
  {
    id: "f06", name: "Wide Straight Denim", category: "Celana",
    price: 499000, salePrice: 419000,
    colors: [{name:"Biru Washed",hex:"#4a5b75"}],
    sizes: ["29","30","32","34"], stock: { "29": 2, "30": 4, "32": 5, "34": 3 },
    rating: 4.7, reviews: 69,
    desc: "Denim wide-straight dengan wash medium-blue dan finishing halus tanpa distressing berlebihan.",
    descEn: "Wide-straight denim in a medium-blue wash with a clean finish, free of excessive distressing.",
    images: ["https://picsum.photos/seed/frennz-f06a/900/1150","https://picsum.photos/seed/frennz-f06b/900/1150"],
    tag: "SALE", bestseller: false
  },
  {
    id: "f07", name: "Structured Cap", category: "Aksesoris",
    price: 179000, salePrice: null,
    colors: [{name:"Hitam",hex:"#111110"},{name:"Krem",hex:"#e7ddc9"}],
    sizes: ["ALL SIZE"], stock: { "ALL SIZE": 22 },
    rating: 4.8, reviews: 97,
    desc: "Topi structured six-panel dengan bordir logo minimal dan strap belakang yang dapat disesuaikan.",
    descEn: "Structured six-panel cap with minimal logo embroidery and an adjustable rear strap.",
    images: ["https://picsum.photos/seed/frennz-f07a/900/1150","https://picsum.photos/seed/frennz-f07b/900/1150"],
    tag: "NEW", bestseller: false
  },
  {
    id: "f08", name: "Canvas Tote", category: "Aksesoris",
    price: 149000, salePrice: null,
    colors: [{name:"Natural",hex:"#d8cfb8"}],
    sizes: ["ALL SIZE"], stock: { "ALL SIZE": 30 },
    rating: 4.6, reviews: 44,
    desc: "Tote bag kanvas tebal 12oz dengan tali panjang dan cetakan wordmark kecil di depan.",
    descEn: "Heavy 12oz canvas tote with long straps and a small wordmark print on the front.",
    images: ["https://picsum.photos/seed/frennz-f08a/900/1150","https://picsum.photos/seed/frennz-f08b/900/1150"],
    tag: null, bestseller: false
  },
  {
    id: "f09", name: "Track Jacket Minimal", category: "Hoodie",
    price: 449000, salePrice: null,
    colors: [{name:"Hitam",hex:"#111110"},{name:"Krem",hex:"#e7ddc9"}],
    sizes: ["S","M","L","XL"], stock: { S: 5, M: 7, L: 6, XL: 3 },
    rating: 4.6, reviews: 39,
    desc: "Jaket track dengan resleting penuh, panel tanpa jahitan mencolok, dan lapisan dalam tricot ringan.",
    descEn: "Track jacket with a full zip, clean seam-free panels, and light tricot lining.",
    images: ["https://picsum.photos/seed/frennz-f09a/900/1150","https://picsum.photos/seed/frennz-f09b/900/1150"],
    tag: "NEW", bestseller: false
  },
  {
    id: "f10", name: "Ribbed Socks Set", category: "Aksesoris",
    price: 99000, salePrice: 79000,
    colors: [{name:"Mix",hex:"#cbbfa4"}],
    sizes: ["ALL SIZE"], stock: { "ALL SIZE": 48 },
    rating: 4.8, reviews: 133,
    desc: "Satu set tiga pasang kaos kaki rib tinggi dengan logo woven kecil di bagian atas.",
    descEn: "A set of three pairs of high rib socks with a small woven logo at the top.",
    images: ["https://picsum.photos/seed/frennz-f10a/900/1150","https://picsum.photos/seed/frennz-f10b/900/1150"],
    tag: "SALE", bestseller: true
  },
  {
    id: "f11", name: "Boxy Tee Washed", category: "T-Shirt",
    price: 229000, salePrice: null,
    colors: [{name:"Abu",hex:"#9a9691"},{name:"Hitam",hex:"#111110"}],
    sizes: ["S","M","L","XL"], stock: { S: 6, M: 6, L: 6, XL: 6 },
    rating: 4.4, reviews: 27,
    desc: "T-shirt boxy-fit dengan proses garment wash agar tekstur terasa lebih lembut sejak pemakaian pertama.",
    descEn: "Boxy-fit tee, garment-washed for a softer texture from the very first wear.",
    images: ["https://picsum.photos/seed/frennz-f11a/900/1150","https://picsum.photos/seed/frennz-f11b/900/1150"],
    tag: null, bestseller: false
  },
  {
    id: "f12", name: "Quilted Vest", category: "Hoodie",
    price: 399000, salePrice: null,
    colors: [{name:"Hitam",hex:"#111110"}],
    sizes: ["S","M","L","XL"], stock: { S: 3, M: 4, L: 4, XL: 1 },
    rating: 4.5, reviews: 22,
    desc: "Rompi quilted ringan dengan resleting penuh, cocok dilayer di atas hoodie atau kemeja.",
    descEn: "Lightweight quilted vest with a full zip — perfect layered over a hoodie or shirt.",
    images: ["https://picsum.photos/seed/frennz-f12a/900/1150","https://picsum.photos/seed/frennz-f12b/900/1150"],
    tag: "NEW", bestseller: false
  }
];

const CATEGORIES = [
  { name: "Hoodie", en: "Hoodies", icon: "🧥" },
  { name: "T-Shirt", en: "T-Shirts", icon: "👕" },
  { name: "Crewneck", en: "Crewnecks", icon: "🎽" },
  { name: "Kemeja", en: "Shirts", icon: "👔" },
  { name: "Celana", en: "Pants", icon: "👖" },
  { name: "Aksesoris", en: "Accessories", icon: "🧢" }
];

const TESTIMONIALS = [
  { name: "Aditya R.", role: "Pelanggan sejak 2024", roleEn: "Customer since 2024", text: "Bahannya berat dan jahitannya rapi banget, jauh dari ekspektasi untuk harga segini. Repeat order terus.", textEn: "The fabric is heavyweight and the stitching is immaculate — way beyond my expectations at this price. I keep reordering.", rating: 5 },
  { name: "Nadia P.", role: "Pelanggan setia", roleEn: "Loyal customer", text: "Packaging-nya niat, ukurannya pas sesuai chart, dan pengiriman selalu cepat. Frennz.Stuff langganan tetap saya.", textEn: "The packaging is thoughtful, the sizing matches the chart exactly, and delivery is always fast. Frennz.Stuff is my go-to store.", rating: 5 },
  { name: "Bimo S.", role: "Pembeli pertama", roleEn: "First-time buyer", text: "Awalnya ragu belanja online, tapi setelah terima hoodie-nya langsung suka. Warnanya sama persis dengan foto.", textEn: "I was hesitant about shopping online at first, but the moment I got the hoodie I loved it. The color is exactly like the photos.", rating: 4 },
  { name: "Clara W.", role: "Pelanggan sejak 2023", roleEn: "Customer since 2023", text: "Desainnya simpel tapi ga pasaran. Selalu jadi favorit tiap kali dipakai kondangan santai atau nongkrong.", textEn: "Simple but unique designs. Always my favorite for casual events and hanging out.", rating: 5 }
];

function getProducts() {
  if (window.SERVER_PRODUCTS && window.SERVER_PRODUCTS.length) return window.SERVER_PRODUCTS;
  const stored = localStorage.getItem("frennz_products");
  if (stored) { try { return JSON.parse(stored); } catch (e) {} }
  return DEFAULT_PRODUCTS;
}
function saveProducts(list) { localStorage.setItem("frennz_products", JSON.stringify(list)); }
function getProductById(id) { return getProducts().find(p => String(p.id) === String(id)); }

function getCategories() {
  if (window.SERVER_CATEGORIES && window.SERVER_CATEGORIES.length) return window.SERVER_CATEGORIES;
  const stored = localStorage.getItem("frennz_categories");
  if (stored) { try { return JSON.parse(stored); } catch (e) {} }
  return CATEGORIES;
}
function saveCategories(list) { localStorage.setItem("frennz_categories", JSON.stringify(list)); }
