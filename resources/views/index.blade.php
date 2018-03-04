@extends('layouts.app')

@section('top-content')
  <section class="metaslider">
    {!! do_shortcode("[metaslider id=36]"); !!}
  </section>

  <section id="cardapio" class="cardapio-section">
    <div class="container">
      <h2 class="raminho text-center">Cardápio</h2>
      <div class="d-flex justify-content-center justify-content-md-between flex-wrap">
        <?php
        $menu_types = get_terms(array(
          'taxonomy' => 'rm-menu-type',
          'hide_empty' => false,
          // 'meta_key' => 'tax_position',
          // 'orderby' => 'tax_position'
        ));

        foreach ( $menu_types as $menu_type ) {?>
          <?php if (has_term_thumbnail($menu_type->term_id) ) { ?>
            <a href="{!!get_link_by_slug('cardapio', 'page')!!}#{!!$menu_type->slug!!}" class="menu-type">
              <?php the_term_thumbnail( $menu_type->term_id, $size = 'medium', 'class=thumbnail' ) ?>
              <h4 class="title"><?php echo $menu_type->description; ?></h4>
            </a>
          <?php } ?>
        <?php } ?>
        <a href="{!!get_link_by_slug('realizar-pedido', 'page')!!}" class="menu-type">
          <img src="@asset('images/monte.jpg')" class="thumbnail" alt="">
          <h4 class="title">Monte seu <br>kit festa</h4>
        </a>
        <a href="{!!get_link_by_slug('cardapio', 'page')!!}" class="btn btn-lg btn-outline-light">ver cardápio completo <i class="arrow-right"></i></a>
      </div>
    </div>
  </section>

  <section id="quem-somos" class="quem-somos">
    <div class="container">
      <div class="section-content">
        <?php $quem_somos = get_page_by_path('quem-somos');?>
        <h2 class="raminho-p text-center">{!! get_the_title( $quem_somos ); !!}</h2>
        {!! apply_filters('the_content', $quem_somos->post_content); !!}
      </div>
    </div>
  </section>
@endsection

@section('content')
  <div id="contato" class="container contact-form">
    <h2 class="raminho-p text-center">Contato</h2>
    <div>
      <h4>Dúvidas? Sugestões? Envie-nos uma mensagem!</h4>

      <form id="contactForm" name="contactForm" submit="sendContactForm(); return false;" method="post">
        <div class="row">
          <div class="col-7">
            <input type="text" name="name" class="form-control" placeholder="Nome" minlength="3" required>
          </div>
          <div class="col">
            <input type="text" name="phone" class="form-control" placeholder="Telefone" minlength="14">
            <!-- <span>Preencha o telefone corretamente</span> -->
          </div>
        </div>
        <div class="row">
          <div class="col-7">
            <input type="text" name="address" class="form-control" placeholder="Endereço">
            <!-- <span>Preencha o endereço corretamente</span> -->
          </div>
          <div class="col">
            <input type="email" name="email" class="form-control" placeholder="E-mail" required>
            <!-- <span>Preencha o email corretamente</span> -->
          </div>
        </div>
        <textarea name="message" class="form-control" rows="5" placeholder="Mensagem" required></textarea>
        <button class="btn btn-lg btn-primary">ENVIAR MENSAGEM</button>
      </form>
    </div>
  </div>
@endsection
