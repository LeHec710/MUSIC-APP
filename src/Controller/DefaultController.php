<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\ArtistRepository;
use App\Repository\SongRepository;
use App\Repository\UserRepository;
use App\Repository\PlaylistRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends AbstractController
{
    // HOMEPAGE - CONNEXION
    #[Route('/', name: 'homepage')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('explore');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        if($lastUsername) {
            return $this->redirectToRoute('explore');
        }
        return $this->render('default/index.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }
    
    // HOMEPAGE - CONNEXION
    #[Route('/test_login', name: 'test_login')]
    public function test_login(): Response
    {
        return $this->render('registration/register.html.twig');
    }

    // EXPLORE
    #[Route('/explore', name: 'explore')]
    public function explore(PlaylistRepository $playlistRepository, ArtistRepository $artistRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $playlists = $playlistRepository->findAll();
        $artists = $artistRepository->findAll();
        return $this->render('base.html.twig', [ // change base to twig page path if you wan't to render the twig page
            'playlists' => $playlists,
            'artists' => $artists,
        ]);
    }

    // SEARCH
    #[Route('/search', name: 'search')]
    public function search(playlistRepository $playlistRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(SearchType::class, null, [
            'method' => 'GET'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $search = $playlistRepository->findByTitle($data["search"]);
            $message = '';
            if($search == null) {
                $message = 'Aucune playlsit trouvée.';
            }

            return $this->render('base.html.twig', [ // change base to twig page path if you wan't to render the twig page
                'message' => $message,
                'form' => $form->createView(),
                'results' => $search
            ]);
        }

        return $this->render('base.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // LOVED TRACKS
    #[Route('/loved', name: 'loved_tracks')]
    public function loved_track(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $tracks = $user->getLovedSongs();
        return $this->render('base.html.twig', [ // change base to twig page path if you wan't to render the twig page
            'tracks' => $tracks,
        ]);
    }
    // HIDDEN BECAUSE VUE PAGE
    // #[Route('/add_loved_track/{id}', name: 'add_love_track')]
    // public function add_love_track(int $id, SongRepository $songRepository, Request $request): RedirectResponse
    // {
    //     $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    //     $em = $this->getDoctrine()->getManager();

    //     $user = $this->get('security.token_storage')->getToken()->getUser();
    //     $song = $songRepository->findOneBy(['id' => $id]);
    //     $user->addLovedSong($song);
    //     $em->persist($user);
    //     $em->flush();

    //     $previousUrl = $request->headers->get('referer');
    //     return $this->redirect($previousUrl);

    // }
    // #[Route('/remove_loved_track/{id}', name: 'remove_love_track')]
    // public function remove_love_track(int $id, SongRepository $songRepository, Request $request): RedirectResponse
    // {
    //     $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    //     $em = $this->getDoctrine()->getManager();

    //     $user = $this->get('security.token_storage')->getToken()->getUser();
    //     $song = $songRepository->findOneBy(['id' => $id]);
    //     $user->removeLovedSong($song);
    //     $em->persist($user);
    //     $em->flush();
        
    //     $previousUrl = $request->headers->get('referer');
    //     return $this->redirect($previousUrl);
    // }

    // PARAMETERS
    #[Route('/parameters', name: 'parameters')]
    public function account(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('default/parameters.html.twig', [
            'user' => $user
        ]);
    }
}
