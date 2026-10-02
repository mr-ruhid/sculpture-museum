    public function home($locale)
    {
        App::setLocale($locale);

        $sculptures = Sculpture::with('translations')
            ->where('is_published', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($s) use ($locale) {
                $tr = $s->translation($locale);
                return [
                    'id' => $s->id,
                    'lat' => (float) $s->latitude,
                    'lng' => (float) $s->longitude,
                    'title' => $tr?->title ?? '',
                    'city' => $tr?->city ?? '',
                    'image' => $s->main_image ? asset('storage/' . $s->main_image) : null,
                    'panorama' => $s->panorama_embed,
                    'url' => url('/' . $locale . '/sculptures/' . $s->slug),
                ];
            })
            ->values();

        return view('theme.rjmuseum.pages.home', compact('sculptures'));
    }
