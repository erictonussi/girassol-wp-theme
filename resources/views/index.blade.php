@extends('layouts.app')

@section('top-content')
  <section class="metaslider">
    {!! do_shortcode("[metaslider id=36]"); !!}
  </section>

  <section id="cardapio" class="cardapio">
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
          <!-- <div class=""> -->
            <a class="menu-type" href="#">
              <?php the_term_thumbnail( $menu_type->term_id, $size = 'medium', 'class=thumbnail' ) ?>
              <h4 class="title"><?php echo $menu_type->description; ?></h4>
            </a>
        <?php } ?>
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

  @include('partials.page-header')

  @if (!have_posts())
    <div class="alert alert-warning">
      {{ __('Sorry, no results were found.', 'sage') }}
    </div>
    {!! get_search_form(false) !!}
  @endif

  @while (have_posts()) @php(the_post())
    @include('partials.content-'.get_post_type())
  @endwhile

  {!! get_the_posts_navigation() !!}
@endsection
