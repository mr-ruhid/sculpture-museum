    public function panorama($locale, $slug)
    {
        App::setLocale($locale);

        $sculpture = Sculpture::with('translations')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        if (!$sculpture->panorama_embed) {
            abort(404);
        }

        return view('theme.rjmuseum.pages.panorama', compact('sculpture'));
    }
