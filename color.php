<div class="container py-4">
  <div class="text-center">
    <!-- İkon -->
    <p class="">
        <i class="fi tick fi-ss-badge-check ps-1" style="padding: 20px; border-radius: 12px; color: white; font-size: 48px;"></i>
    </p>
    
    <!-- Renk seçim alanları -->
    <form class="d-flex gap-3 align-items-center flex-wrap justify-content-center" style="min-width: 280px;">
      <div class="form-group">
        <label for="color1" class="form-label mb-1">Renk 1</label>
        <input type="color" id="color1" class="form-control form-control-color p-0" value="#00c6ff" title="Renk 1">
      </div>
      <div class="form-group">
        <label for="color2" class="form-label mb-1">Renk 2</label>
        <input type="color" id="color2" class="form-control form-control-color p-0" value="#0072ff" title="Renk 2">
      </div>
    </form>
  </div>
</div>

<script>
  const icon = document.querySelector('i.fi.tick.fi-ss-badge-check.ps-1');
  const color1 = document.getElementById('color1');
  const color2 = document.getElementById('color2');

  function updateGradient() {
    icon.style.background = `linear-gradient(to right, ${color1.value}, ${color2.value})`;
    icon.style.color = '#fff';
  }

  color1.addEventListener('input', updateGradient);
  color2.addEventListener('input', updateGradient);

  // İlk ayar
  updateGradient();
</script>
<style>
    ul li div{
        background: transparent!important;
        color: #fff;
    }
    
    ul li{
        background: transparent!important;
    }
</style>
<ul class="list-group mt-3 row mb-5 col-11 m-auto">
    <?php
    $renkler = $db->Get("Renk", true);
    foreach($renkler as $renk){ ?>
        <li class="list-group-item text-center">
            <div class="row row-cols-2 fs-2">
                <div class="col"><?= $renk["class"]; ?></div>
                <div class="col"><i class="fi tick fi-ss-badge-check ps-1 <?= $renk["class"]; ?>"></i></div>
            </div>
        </li>
    <?php } ?>
</ul>